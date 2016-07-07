<?php

define('LOGIN_CHECK', false);

require_once __DIR__  . '/../../include/core.php';
require_once INCLUDE_DIR  . 'customers.php';

$id = filter_input(INPUT_GET, 'id');
$hash = filter_input(INPUT_GET, 'hash');

$customer = new App\Customer($id);

if (!$customer->exists() || !$customer->isHashValid($hash)) {
    # show error page with "broken validation link" message
    echo $twig->render('Signup/link-error.html.twig', [
        'url' => getPath('login')
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

if (!$customer->activateAccount()) {
    # show db access error page
     echo $twig->render('Signup/db-access-error.html.twig', [
        'message' => 'Some arror happens. Please, try to verify your account later.'
        ]);
    exit;
}

# show sccess validation page
echo $twig->render('Signup/success-verification.html.twig', [
    'customer' => $customer,
    'url' => getPath('login'),
    ]);
