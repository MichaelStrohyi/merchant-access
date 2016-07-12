<?php

define('LOGIN_CHECK', false);

require_once __DIR__  . '/../include/core.php';
require_once INCLUDE_DIR  . 'customers.php';

$customer_id = filter_input(INPUT_GET, 'id');
$customer = new App\Customer($customer_id);

if (!$customer->exists()) {
    # show error page with "account is already validated, please login" message
    echo $twig->render('Signup/customer-not-exists.html.twig', [
        'url' => getPath('registration')
        ]);
    exit;
}

if (!$customer->isWaitingValidation()) {
    # show error page with "account is already validated, please login" message
    echo $twig->render('Signup/customer-exists.html.twig', [
        'url' => getPath('login')
        ]);
    exit;
}
 # send email to confirm registration
emailConfirmation('account_verification', ['customer' => $customer]);

# display success registration page
echo $twig->render('Signup/success-registration.html.twig', [
    'customer' => $customer,
    ]);
