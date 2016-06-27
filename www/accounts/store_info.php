<?php

require_once __DIR__  . '/../../include/core.php';

$store_id = filter_input(INPUT_GET, 'store');
$store = new App\Store($store_id);

$customer = getLoggedCustomer();

if ($customer->getId() != $store->getOwner()) {
    echo "You have no permission to work with this store";
    exit;
}

if (!$store->isActive()) {
    echo "You have not validated this store ownership yet. Please, validate it.";
    exit;
}

echo "Congratulations, you can view this store info";
