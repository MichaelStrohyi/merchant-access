<?php

require_once __DIR__  . '/../../include/core.php';

$store_id = filter_input(INPUT_GET, 'store');
$customer = getLoggedCustomer();

$store = new App\Store($customer, $store_id);

if (!customerCanWork($store)) {
    exit;
}

echo "Congratulations, you can view this store info";
