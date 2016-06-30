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

if (!$store->exists()) {
    echo $twig->render('Panel/store-access-denied.html.twig', [
        'message' => 'You have no permission to work with this store',
        'url' => getPath('stores'),
        ]);
    exit;
}

if (!$store->isActive()) {    
    echo $twig->render('Panel/store-access-denied.html.twig', [
        'message' => 'You have not validated this store ownership yet. Please, validate it or <a href="' . getPath('resend_verification', ['store' => $store]) . '">click here</a> to request new validation email',
        'url' => getPath('stores'),
        ]);
    exit;
}

if (isset($button_submit)) {
    $store_name = $store->getName();

    emailConfirmation('store_rm_verification', ['store' => $store]);

     # display success store deleting page
    echo $twig->render('Panel/success-store-rm.html.twig', [
        'store' => $store,
        ]);
    exit;
}

echo $twig->render('Panel/store-rm.html.twig', [
    'store' => $store
    ]);

