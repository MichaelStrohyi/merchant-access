<?php

require_once __DIR__  . '/../../../include/core.php';

# get store_id from $_POST
$store_id = filter_input(INPUT_POST, 'store_id');

# get removed_coupons from $_POST
$removed_coupons = filter_input(INPUT_POST, 'rCoupons');

# if $store_id is not set get it from $_GET
if (!isset($store_id_post)) {
    # get variables from $_GET
    $store_id = filter_input(INPUT_GET, 'store');
}

# get variables from $_POST
$coupons_data = filter_input(INPUT_POST, 'coupons', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$buttons_data = filter_input(INPUT_POST, 'buttons', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

# unset flag 'coupons is updated'
$message = '';

# check if current $customer has logged in
$customer = getLoggedCustomer();

$store = new App\Store($customer, $store_id);

# check if current $customer has access to $store
$access_error = $customer->checkStoreAccess($store);
if (!empty($access_error)) {
    showErrorPage($store, $access_error);
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
    # null error flag and coupon's position array
    $error = false;
    $coupons_position = [];

    # if there are coupons in user submitted data
    if (!empty($coupons_data)) {
        # go over these coupons
        foreach ($coupons_data as $coupon_id => $value) {
            # if current coupon is new
            if (strpos($coupon_id, 'nc')!== false) {
                #add it into coupons array
                $store->addNewCoupon($coupon_id);
            }

            # add coupon id into position array
            $coupons_position [] = $coupon_id;
            # fetch coupon's info from user submitted data
            $store->getCoupons()[$coupon_id]->fetchInfo($value);
            # validate coupon with new info
            $store->getCoupons()[$coupon_id]->validate();

            # if coupon not valid # set flag "error happened"
            if (!$store->getCoupons()[$coupon_id]->isValid()) {
                $error = true;
            }
        }

        # sort store coupons according to $coupons_position
        $store->sortCoupons($coupons_position);
    }

    # if no error happened
    if (!$error) {
        # delete removed coupons
        if (!empty($removed_coupons) && !$store->deleteRemovedCoupons(explode(',', $removed_coupons))) {
            # if removed coupon was not deleted from db
            if (!(defined('ENVIRONMENT') && ENVIRONMENT == 'development')) {
                $message = 'Some arror happens. Please, try again later.';
            } else {
                $message = '<table border=3><tr><td bgcolor="yellow">Error happens trying to delete removed coupons</tr></td></table>';
            }

            # display error page
            echo $twig->render('Signup/db-access-error.html.twig', [
                'customer' => $store->getCustomer(),
                'message' => $message,
                ]);
            exit;
        }

        # save coupons into db
        if (!$store->saveCoupons()) {
            # if logo was not deleted create error message depending on working mode
            if (!(defined('ENVIRONMENT') && ENVIRONMENT == 'development')) {
                $message = 'Some arror happens. Please, try again later.';
            } else {
                $message = '<table border=3><tr><td bgcolor="yellow">Error happens trying to save store coupons</tr></td></table>';
            }

            # display error page
            echo $twig->render('Signup/db-access-error.html.twig', [
                'customer' => $store->getCustomer(),
                'message' => $message,
                ]);
            exit;
        }

        # set flag "coupons is updated"
        $message = 'saved';
    }

}


# display coupons page
echo $twig->render('Coupons/coupons-list.html.twig', [
    'store' => $store,
    'url' => getPath('image'),
    'message' => $message,
    'coupons_js' => getPath('coupons_js'),
    ]);
