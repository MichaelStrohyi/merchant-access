<?php

require_once __DIR__  . '/../../include/core.php';

#get customer from session
$customer = getLoggedCustomer();

#get list of customer's stores
$stores = $customer->getStores();

#render page with list of customer's stores
$routes = ['store_info' => getPath('store_info'),
    'store_delete' => getPath('store_rm'),
    'store_coupons' => getPath('store_coupons'),
    'store_add' => getPath('store_add'),
    ];
echo $twig->render('Panel/stores.html.twig', [
    'customer' => $customer,
    'routes' => $routes,
    ]);