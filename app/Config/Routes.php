<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('dashboard', 'Home::index');

$routes->get('account-statement', 'AccountStatement::index');
$routes->post('account-statement/view-account-statement', 'AccountStatement::view_account_statement');

$routes->get('outstanding-loans', 'OutstandingLoans::index');
$routes->get('outstanding-loans/view-outstanding-loan/(:num)', 'OutstandingLoans::view_outstanding_loan/$1');

$routes->get('finished-loans', 'FinishedLoans::index');
$routes->get('finished-loans/view-finished-loan/(:num)', 'FinishedLoans::view_finished_loan/$1');

$routes->get('loan-application', 'LoanApplication::index');
$routes->get('loan-application/get-loan-setup-details/(:any)', 'LoanApplication::get_loan_setup_details/$1');
$routes->post('loan-application/check-guarantor', 'LoanApplication::check_guarantor');
$routes->get('loan-application/check-external-guarantor', 'LoanApplication::search_external_guarantor');
$routes->post('loan-application/submit-application', 'LoanApplication::submit_loan_application');
$routes->post('loan-application/confirm-guarantor', 'LoanApplication::confirm_guarantor');
$routes->post('loan-application/reject-guarantor', 'LoanApplication::reject_guarantor');

$routes->get('withdrawal-application', 'WithdrawalApplication::index');
$routes->get('withdrawal-application/compute-balance/(:any)', 'WithdrawalApplication::compute_balance/$1');
$routes->post('withdrawal-application/submit-application', 'WithdrawalApplication::submit_withdrawal_application');

$routes->get('savings-variation', 'SavingsVariation::index');

$routes->get('deposit-lodgement', 'DepositLodgement::index');
$routes->get('deposit-lodgement/get-active-loans', 'DepositLodgement::get_active_loans');
$routes->get('deposit-lodgement/get-savings-types', 'DepositLodgement::get_savings_types');
$routes->post('deposit-lodgement/submit-deposit', 'DepositLodgement::submit_deposit');

$routes->get('notifications', 'Notifications::index');
$routes->get('unread-notifications', 'Notifications::unread_notifications');
$routes->get('get-user-notifications', 'Notifications::get_user_notifications');
$routes->get('notifications/view-notification/(:num)', 'Notifications::view_notification/$1');

$routes->get('account', 'Account::index');
$routes->post('account/upload-display-picture', 'Account::upload_display_picture');
$routes->post('account/change-password', 'Account::change_password');
$routes->post('account/update-bank', 'Account::update_bank');
$routes->post('account/update-nok', 'Account::update_nok');
$routes->post('account/details', 'Account::update_details');
$routes->get('security', 'Account::security');

$routes->post('savings-account', 'SavingsAccount::new_savings_account');
$routes->get('savings-account/fee/(:num)', 'SavingsAccount::get_contribution_type_fee/$1');

$routes->get('auth/login', 'Auth::login');
$routes->get('auth/membership', 'Auth::membership');
$routes->post('login', 'Auth::auth_login');
$routes->post('membership', 'Auth::auth_membership');
$routes->get('logout', 'Auth::logout');

// API Routes for mobile
$routes->post('api/auth/register', 'API\Auth::post_register');
$routes->patch('api/auth/register', 'API\Auth::patch_register');
$routes->post('api/auth/login', 'API\Auth::post_login');
$routes->post('api/auth/forgot-password', 'API\Auth::post_forgot_password');
$routes->patch('api/auth/reset-password', 'API\Auth::patch_reset_password');
$routes->get('api/auth/cooperator', 'API\Auth::get_cooperator_details', ['filter' => 'authFilter']);
$routes->patch('api/auth/cooperator/details', 'API\Auth::patch_cooperator_details', ['filter' => 'authFilter']);
$routes->patch('api/auth/cooperator/password', 'API\Auth::patch_cooperator_password', ['filter' => 'authFilter']);
$routes->post('api/auth/cooperator/enquiry', 'API\Auth::post_cooperator_enquiry', ['filter' => 'authFilter']);

$routes->get('api/account', 'API\Account::get_all_account_details', ['filter' => 'authFilter']);
$routes->get('api/account/types', 'API\Account::get_all_account_types', ['filter' => 'authFilter']);
$routes->post('api/account', 'API\Account::post_new_savings_account', ['filter' => 'authFilter']);

$routes->get('api/loan/(:num)', 'API\Loan::get_loan/$1', ['filter' => 'authFilter']);
$routes->post('api/loan', 'API\Loan::post_new_loan_application', ['filter' => 'authFilter']);
$routes->get('api/loan/setup', 'API\Loan::get_loan_setups', ['filter' => 'authFilter']);
$routes->get('api/loan/setup/(:num)', 'API\Loan::get_loan_setup/$1', ['filter' => 'authFilter']);
$routes->get('api/loan/outstanding', 'API\Loan::get_outstanding_loans', ['filter' => 'authFilter']);
$routes->get('api/loan/guarantor/external', 'API\Loan::search_loan_external_guarantor', ['filter' => 'authFilter']);
$routes->get('api/loan/guarantor/internal', 'API\Loan::search_loan_internal_guarantor', ['filter' => 'authFilter']);

$routes->get('api/withdrawal/balance', 'API\Withdrawal::get_computed_balance', ['filter' => 'authFilter']);
$routes->post('api/withdrawal', 'API\Withdrawal::post_new_withdrawal_application', ['filter' => 'authFilter']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
