<?php

define('LOGIN_CHECK', false);

require_once __DIR__  . '/../../include/core.php';

$error = [];

$login_data = filter_input(INPUT_POST, 'login', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

if (!empty($login_data)) {    
    # steps to do when form was submitted
    $email = empty($login_data['email']) ? '' : $login_data['email'];
    $password = empty($login_data['password']) ? '' : $login_data['password'];

    $customer = App\Customer::findByEmail($email);

    if (!($customer->exists() && $customer->isPasswordMatch($password))) {
        $error = ['string' => 'Invalid email or password. Please try again.'];
    } elseif (!$customer->isActive()) {
        $error = ['string' => 'Your account is not validated. Please, use link from validation mail.',
            'url' => getPath('resend_customer_verification', ['customer' => $customer]),
            ];
    } else {
        setLoggedCustomer($customer);
       
        redirectToPage('main');
        exit;
    }
}

echo $twig->render('Panel/login.html.twig', [
    'error' => $error
    ]);
