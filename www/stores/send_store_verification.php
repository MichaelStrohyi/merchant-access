<?php

require_once __DIR__  . '/../../include/core.php';
require_once INCLUDE_DIR  . 'customers.php';

$store_id = filter_input(INPUT_GET, 'store');
$action = filter_input(INPUT_GET, 'action');
$customer = getLoggedCustomer();

$store = new App\Store($customer, $store_id);

if (!$store->exists()) {
    echo $twig->render('Stores/store-access-denied.html.twig', [
        'message' => 'You have no permission to work with this store',
        'url' => getPath('stores'),
        ]);
    exit;
}

switch ($action) {
    case 'add':
        if (!$store->isWaitingValidation()) {
            echo $twig->render('Stores/store-access-denied.html.twig', [
                'message' => 'Store ' . $store->getName() . 'is already validated',
                'url' => getPath('stores'),
                ]);
            exit;
        }
        # send email to confirm store adding
        emailConfirmation('store_verification', ['store' => $store]);

        # display success store adding page
        echo $twig->render('Stores/success-store-add.html.twig', [
            'store' => $store,
            'url' => getPath('stores'),
            ]);
        exit;
        break;

    case 'rm':
        if (!$store->isWaitingRemoving()) {
            echo $twig->render('Stores/store-access-denied.html.twig', [
                'message' => 'Store ' . $store->getName() . 'does not need removing validation',
                'url' => getPath('stores'),
                ]);
            exit;
        }
        # send email to confirm store adding
        emailConfirmation('store_rm_verification', ['store' => $store]);

        # display success store adding page
        echo $twig->render('Stores/success-store-rm.html.twig', [
            'store' => $store,
            'url' => getPath('stores'),
            ]);
        exit;
        break;
}

redirectToPage('stores');
