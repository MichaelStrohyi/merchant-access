<?php

require_once __DIR__  . '/../../include/core.php';

# get variables from $_GET
$store_id = filter_input(INPUT_GET, 'store');

# get variables from $_POST
$form_data = filter_input(INPUT_POST, 'store', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

# get store_id from $_POST if it is set
if (isset($form_data['id'])) {
    $store_id = $form_data['id'];

}

$message = '';

# check if current $customer has logged in
$customer = getLoggedCustomer();

$store = new App\Store($customer, $store_id);
$new_logo = new App\StoreLogo($store);

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

# if button Save is pressed
if (isset($form_data['buttonSave']))
    {
    # grab data from $form_data to $store vars
    $store->fetchInfo($form_data);
    # if logo was removed
    if (isset($form_data['removePrimaryLogo'])) {
        # delete primary logo from db
        if (!$store->getPrimaryLogo()->delete()) {
            if (!(defined('ENVIRONMENT') && ENVIRONMENT == 'development')) {
                $message = 'Some arror happens. Please, try again later.';
            } else {
                $message = '<table border=3><tr><td bgcolor="yellow">Error happens trying to delete Primary Logo</tr></td></table>';
            }

            echo $twig->render('Signup/db-access-error.html.twig', [
                'customer' => $store->getCustomer(),
                'message' => $message,
                ]);
        }
    }

    # if new logo is set
    if (!empty($_FILES['new_logo']['name']) && $_FILES['new_logo']['error'] == 0) {
        $new_logo->gatherFileInfo($_FILES['new_logo']);
        $new_logo->validate();
        # if new logo not valid
        if (!$new_logo->isValid()) {
            echo $twig->render('Stores/store-info.html.twig', [
                'store' => $store,
                'message' => $message,
                'new_logo' => $new_logo,
                'url' => getPath('image'),
                ]);
            exit;
        }

        if (!$new_logo->save() || $store->getPrimaryLogo()->exists() && !$store->getPrimaryLogo()->delete()) {
            echo $twig->render('Signup/db-access-error.html.twig', [
                    'customer' => $store->getCustomer(),
                    'message' => 'Some arror happens. Please, try again later.',
                    ]);
            exit;
        }
    }

    # save $store into db
    $store->save();
    $store->getLogosList();

    $message = 'saved';
}

# display store-info page
echo $twig->render('Stores/store-info.html.twig', [
    'store' => $store,
    'message' => $message,
    'new_logo' => $new_logo,
    'url' => getPath('image'),
    ]);
