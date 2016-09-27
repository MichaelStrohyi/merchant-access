<?php

define('LOGIN_CHECK', false);

require_once __DIR__  . '/../../include/core.php';
require_once INCLUDE_DIR  . 'customers.php';

$error = [];
$email = '';

$login_data = filter_input(INPUT_POST, 'login', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

if (!empty($login_data)) {    
    # steps to do when form was submitted
    $email = empty($login_data['email']) ? '' : $login_data['email'];

    $customer = App\Customer::findByEmail($email);

    if (!$customer->exists()) {
        $error = ['string' => 'Account with such email does not exist.'];
    } elseif (!$customer->isActive()) {
        $error = ['string' => 'Your account is not validated. Please, use link from validation mail.',
            'url' => getPath('resend_customer_verification', ['customer' => $customer]),
            ];
    } else {
        # send email with recovery password link
        emailConfirmation('password_recovery', ['customer' => $customer]);
        
        # display sccess recovery page
        echo $twig->render('Panel/success-password-recovery.html.twig', [
            'customer' => $customer,
            ]);

        exit;
    }
}

echo $twig->render('Panel/password-recovery.html.twig', [ 'email' => $email,
    'error' => $error
    ]);
