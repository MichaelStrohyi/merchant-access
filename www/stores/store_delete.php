<?php

require_once __DIR__  . '/../../include/core.php';
require_once INCLUDE_DIR  . 'customers.php';

$store_id = filter_input(INPUT_GET, 'store');
$button_submit = filter_input(INPUT_POST, 'buttonSubmit');
$button_cancel = filter_input(INPUT_POST, 'buttonCancel');

if (isset($button_cancel)) {
    redirectToPage('stores');
    exit;
}

$customer = getLoggedCustomer();

$store = new App\Store($customer, $store_id);

$access_error = $customer->checkStoreAccess($store);
$store_not_active = $store->isWaitingValidation();

if (!empty($access_error) && !$store_not_active) {
    showErrorPage($store, $access_error);
    exit;
}

if (isset($button_submit)) {

    if (!$store->deleteStore()) {
            # show db access error page
             echo $twig->render('Signup/db-access-error.html.twig', [
                'message' => 'Some arror happens. Please, try again later.'
                ]);
            exit;
        }

    if ($store_not_active) {
        redirectToPage('store_rm_verification', ['store' => $store]);
        exit;
    }

    emailConfirmation('store_rm_verification', ['store' => $store]);

     # display success store deleting page
    echo $twig->render('Stores/success-store-rm.html.twig', [
        'store' => $store,
        'url' => getPath('stores'),
        ]);
    exit;
}

echo $twig->render('Stores/store-rm.html.twig', [
    'store' => $store
    ]);

