<?php

require_once __DIR__  . '/../../../include/core.php';

# get store_id from $_POST
$store_id = filter_input(INPUT_POST, 'store_id');

# if $store_id is not set get it from $_GET
if (!isset($store_id_post)) {
    # get variables from $_GET
    $store_id = filter_input(INPUT_GET, 'store');
}

# get variables from $_POST
$coupons_data = filter_input(INPUT_POST, 'coupons', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$buttons_data = filter_input(INPUT_POST, 'buttons', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

# unset flag 'coupons is updatedd'
$message = '';

# check if current $customer has logged in
$customer = getLoggedCustomer();

$store = new App\Store($customer, $store_id);

# check if current $customer has access to $store
if (!customerCanWork($store)) {
    exit;
}

# if button Cancel is pressed
if (isset($buttons_data['buttonCancel'])) {
    # redirect customer to stores.php
    redirectToPage('stores');
    exit;
}

# if button Save is pressed
if (isset($buttons_data['buttonSave']))
    {
    # grab data from $coupons_data to $coupons vars
    $coupons = $store->getCoupons();
    foreach ($coupons_data as $coupon_id => $value) {
        $coupons[$coupon_id]->fetchInfo($value);
        $coupons[$coupon_id]->validate();
    }
}

# display coupons page
echo $twig->render('Coupons/coupons-list.html.twig', [
    'store' => $store,
    'url' => getPath('image'),
    'message' => $message,
    'new_image' => $new_image,
    ]);
