<?php

require_once __DIR__  . '/../../../include/core.php';

# get variables from $_GET
$store_id = filter_input(INPUT_GET, 'store');

# get store_id from $_POST
$form_data = filter_input(INPUT_POST, 'store_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

# get variables from $_POST
$form_data = filter_input(INPUT_POST, 'store', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

# get store_id from $_POST if it is set
if (isset($form_data['id'])) {
    $store_id = $form_data['id'];

}

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
if (isset($form_data['buttonCancel'])) {
    # redirect customer to stores.php
    redirectToPage('stores');
    exit;
}

// !!! mockup
$new_image[10] = ['errorString' => 'Some error for 1st image',
    'hasError' => true,
    ];
// !!! endof mockup

# display coupons page
echo $twig->render('Coupons/coupons-list.html.twig', [
    'store' => $store,
    'url' => getPath('image'),
    'message' => $message,
    'new_image' => $new_image,
    ]);
