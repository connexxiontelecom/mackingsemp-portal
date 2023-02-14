<?php

namespace App\Controllers\API;

use App\Models\CooperatorModel;
use App\Models\PasswordResetTokenModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class Auth extends ResourceController
{
    use ResponseTrait;

    public function post_register(): Response
    {
        try {
            $cooperatorModel = new CooperatorModel();
            $passwordResetModel = new PasswordResetTokenModel();
            $emailService = \Config\Services::email();

            $account_number = $this->request->getVar('account_number') ?? null;
            if (!$account_number) return $this->fail('Account number is required');

            $cooperator = $cooperatorModel->where('cooperator_staff_id', $account_number)->first();
            if (!$cooperator) return $this->failNotFound('Cooperator with that account number does not exist');

            $imei = $cooperator['cooperator_imei'];
            if ($imei) return $this->fail('Profile already exists. Proceed to login');

            $email = $cooperator['cooperator_email'];
            if (!$email) return $this->fail('Cooperator email does not exist');

            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $plaintext = '';
            for ($i = 0; $i < 6; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $plaintext .= $characters[$index];
            }
            $data['token'] = hash("sha256", $plaintext);
            $data['email'] = $email;
            $passwordResetToken = $passwordResetModel->where('email', $email)->first();
            if ($passwordResetToken) $data['id'] = $passwordResetToken['id'];
            $passwordResetModel->save($data);

            $emailService->setFrom('support@connexxiontelecom.com', 'ICOOP by Connexxion Telecom');
            $emailService->setTo($email);
            $emailService->setSubject('Your Mobile Platform Registration OTP');
            $mail_data = [
                'user' => $cooperator['cooperator_first_name'] . ' ' . $cooperator['cooperator_last_name'],
                'token' => $plaintext,
            ];
            $body = view('mail/register-otp', $mail_data);
            $emailService->setMessage($body);
            if (!$emailService->send(false))
                return $this->failServerError('An error occurred. Please try again later.');

            $response = [
                'success' => true,
                'message' => 'OTP sent to cooperator email',
                'cooperator' => $cooperator
            ];
            return $this->respond($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function patch_register(): Response
    {
        try {
            $cooperatorModel = new CooperatorModel();
            $passwordResetModel = new PasswordResetTokenModel();
            $emailService = \Config\Services::email();

            $imei_number = $this->request->getVar('imei_number') ?? null;
            if (!$imei_number) return $this->fail('IMEI number is required');

            $token = $this->request->getVar('token') ?? null;
            if (!$token) return $this->fail('OTP token is required');

            $hashedToken = hash("sha256", $token);
            $passwordResetToken = $passwordResetModel->where('token', $hashedToken)->first();
            if (!$passwordResetToken) return $this->fail('OTP token is invalid');

            $email = $passwordResetToken['email'];
            $cooperator = $cooperatorModel->where('cooperator_email', $email)->first();
            if (!$cooperator) return $this->fail('Cooperator email is invalid');

            $data['cooperator_id'] = $cooperator['cooperator_id'];
            $data['cooperator_imei'] = $imei_number;
            $cooperatorModel->save($data);

            $passwordResetModel->where('token', $hashedToken)->delete();

            $emailService->setFrom('support@connexxiontelecom.com', 'ICOOP by Connexxion Telecom');
            $emailService->setTo($email);
            $emailService->setSubject('Registration Success');
            $mail_data = [
                'user' => $cooperator['cooperator_first_name'] . ' ' . $cooperator['cooperator_last_name'],
            ];
            $body = view('mail/register-success', $mail_data);
            $emailService->setMessage($body);
            $emailService->send(false);

            $response = [
                'success' => true,
                'message' => 'Registration successful please login to begin'
            ];
            return $this->respond($response);

        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function post_login()
    {
        try {
            $cooperatorModel = new CooperatorModel();
            $emailService = \Config\Services::email();

            $user = $this->request->getVar('user') ?? null;
            if (!$user) return $this->fail('User is required');

            $imei_number = $this->request->getVar('imei_number') ?? null;
            if (!$imei_number) return $this->fail('IMEI number is required');

            $password = $this->request->getVar('password') ?? null;
            if (!$password) return $this->fail('Password is required');

            $cooperator = $cooperatorModel->where('cooperator_email', $user)
                ->orWhere('cooperator_staff_id', $user)
                ->orWhere('cooperator_telephone', $user)
                ->first();
            if (!$cooperator) return $this->failNotFound('Cooperator with those credentials does not exist');

            $cooperator_imei = $cooperator['cooperator_imei'];
            if (!$cooperator_imei) return $this->fail('Profile does not exist, please register on this device');

            if ($cooperator_imei !== $imei_number) return $this->fail('This device is invalid, please unlock your account to use a new device');

            $passwordVerify = password_verify($password, $cooperator['cooperator_password']);
            if (!$passwordVerify) return $this->failNotFound('Cooperator with those credentials does not exist');

            $key = getenv("JWT_SECRET");
            $iat = time();
            $exp = $iat + 3600;
            $payload = array(
                "iss" => "ICOOP by Connexxion Telecom",
                "aud" => "Mackingsemp ICOOP",
                "sub" => $user,
                "iat" => $iat, //Time the JWT issued at
                "exp" => $exp, // Expiration time of token
                "email" => $cooperator['cooperator_email'],
            );
            $token = JWT::encode($payload, $key, 'HS256');

            $emailService->setFrom('support@connexxiontelecom.com', 'ICOOP by Connexxion Telecom');
            $emailService->setTo($cooperator['cooperator_email']);
            $emailService->setSubject('Login Success');
            $mail_data = [
                'user' => $cooperator['cooperator_first_name'] . ' ' . $cooperator['cooperator_last_name'],
            ];
            $body = view('mail/login-success', $mail_data);
            $emailService->setMessage($body);
            $emailService->send(false);

            $response = [
                'success' => true,
                'message' => 'Login successful',
                'token' => $token
            ];
            return $this->respond($response);

        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

}