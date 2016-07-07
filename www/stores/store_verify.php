<?php

define('LOGIN_CHECK', false);

require_once __DIR__  . '/../../include/core.php';
require_once INCLUDE_DIR  . 'customers.php';

$store_id = filter_input(INPUT_GET, 'store');
$action = filter_input(INPUT_GET, 'action');
$customer_id = filter_input(INPUT_GET, 'id');
$hash = filter_input(INPUT_GET, 'hash');

$customer = new App\Customer($customer_id);
$store = new App\Store($customer, $store_id);

if (!$store->exists() || !$store->isHashValid($hash)) {
    # show error page with "broken validation link" message
    echo $twig->render('Signup/link-error.html.twig', [
        'url' => getPath('login')
        ]);
    exit;
}
switch ($action) {
    case 'rm':
        if (!$store->isWaitingRemoving()) {
            # show error page with "this store is not waiting for removing validation" message
            echo $twig->render('Stores/store-not-waiting-rm.html.twig', [
                'url' => getPath('stores')
                ]);
            exit;
        }

        if (!$store->deleteFromList()) {
            # show db access error page
             echo $twig->render('Signup/db-access-error.html.twig', [
                'message' => 'Some arror happens. Please, try to verify later.'
                ]);
            exit;
        }

       break;

    case 'add':
        if (!$store->isWaitingValidation()) {
            # show error page with "this store is already validated" message
            echo $twig->render('Stores/store-already-validated.html.twig', [
                'url' => getPath('stores')
                ]);
            exit;
        }

        if (!$store->activateStore()) {
            # show db access error page
             echo $twig->render('Signup/db-access-error.html.twig', [
                'message' => 'Some arror happens. Please, try to verify later.'
                ]);
            exit;
        }

       break;
}

# show sccess validation page
echo $twig->render('Stores/success-verification.html.twig', [
    'customer' => $customer, 
    'url' => getPath('stores'),
    ]);
