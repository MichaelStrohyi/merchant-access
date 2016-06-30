<?php

require_once __DIR__  . '/../../include/core.php';
require_once INCLUDE_DIR  . 'customers.php';

$store_id = filter_input(INPUT_GET, 'store');
$customer = getLoggedCustomer();

$store = new App\Store($customer, $store_id);

if (!$store->exists()) {
    echo $twig->render('Panel/store-access-denied.html.twig', [
        'message' => 'You have no permission to work with this store',
        'url' => getPath('stores'),
        ]);
    exit;
}

if (!$store->isWaitingValidation()) {    
    echo $twig->render('Panel/store-access-denied.html.twig', [
        'message' => 'Store ' . $store->getName() . 'is already validated',
        'url' => getPath('stores'),
        ]);
    exit;
}

# send email to confirm store adding
emailConfirmation('store_verification', ['store' => $store]);

# display success store adding page
echo $twig->render('Panel/success-store-add.html.twig', [
    'store' => $store,
    ]);