<?php

require_once __DIR__  . '/../../include/core.php';

#get customer from session
$customer = getLoggedCustomer();

#get list of customer's stores
$stores = $customer->getStores();

#render page with list of customer's stores
echo $twig->render('Panel/stores.html.twig', [
    'stores' => $stores
    ]);
