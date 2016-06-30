<?php

require_once __DIR__  . '/../../include/core.php';

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

if (!$store->isActive()) {
    echo $twig->render('Panel/store-access-denied.html.twig', [
        'message' => 'You have not validated this store ownership yet. Please, validate it or <a href="' . getPath('resend_verification', ['store' => $store]) . '">click here</a> to request new validation email',
        'url' => getPath('stores'),
        ]);
    exit;
}

echo "Congratulations, you can view this store coupons";
