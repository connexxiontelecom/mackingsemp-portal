<?php

namespace App\Controllers\API;

use App\Models\CooperatorModel;
use App\Models\PasswordResetTokenModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;

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

            $emailService->setFrom('info@mackingsemp.com', 'Mackingsemp ICOOP');
            $emailService->setTo($email);
            $emailService->setSubject('Your Registration OTP');
            $emailService->setMailType('html');
            $mail_data = [
                'user' => $cooperator['cooperator_first_name'] . ' ' . $cooperator['cooperator_last_name'],
                'token' => $plaintext,
            ];
            $body = view('mail/register-otp', $mail_data);
            $emailService->setMessage($body);
            if (!$emailService->send(false)) return $this->failServerError('An error occurred. Please try again later.');

            $response = [
                'success' => true,
                'message' => 'OTP sent to cooperator email'
            ];
            return $this->respond($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function patch_register()
    {
        try {
            $account_number = $this->request->getVar('account_number') ?? null;
            $account_number = $this->request->getVar('account_number') ?? null;
            $account_number = $this->request->getVar('account_number') ?? null;

        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

}