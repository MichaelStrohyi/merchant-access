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

# unset flag 'store is saved'
$message = '';

# check if current $customer has logged in
$customer = getLoggedCustomer();

$store = new App\Store($customer, $store_id);
$new_logo = new App\StoreLogo($store);

# check if current $customer has access to $store
$access_error = $customer->checkStoreAccess($store);
if (!empty($access_error)) {
    showErrorPage($store, $access_error);
    exit;
}

# if button Cancel is pressed
if (isset($form_data['buttonCancel'])) {
    # redirect customer to stores.php
    redirectToPage('stores');
    exit;
}

# grab data from $form_data to $store vars
if (isset($form_data)) {
    $store->fetchInfo($form_data);
}

# delete primary logo from db if customer chose to delete logo
if (isset($form_data['removePrimaryLogo']) && !$store->deletePrimaryLogo()) {
    # if logo was not deleted create error message depending on working mode
    if (!(defined('ENVIRONMENT') && ENVIRONMENT == 'development')) {
        $message = 'Some arror happens. Please, try again later.';
    } else {
        $message = '<table border=3><tr><td bgcolor="yellow">Error happens trying to delete Primary Logo</tr></td></table>';
    }
    # display error page
    echo $twig->render('Signup/db-access-error.html.twig', [
        'customer' => $store->getCustomer(),
        'message' => $message,
        ]);
    exit;
}

# if new logo is set
if (!empty($_FILES['new_logo']['name']) && $_FILES['new_logo']['error'] == 0) {
    # validate new logo
    $new_logo->gatherFileInfo($_FILES['new_logo']);
    $new_logo->validate();
    # if new logo not valid
    if (!$new_logo->isValid()) {
        # display error page
        echo $twig->render('Stores/store-info.html.twig', [
            'store' => $store,
            'message' => $message,
            'new_logo' => $new_logo,
            'url' => getPath('image'),
            ]);
        exit;
    }
    # load new logo into primary logo
    $store->getPrimaryLogo()->gatherFileInfo($_FILES['new_logo']);

# if button Save is pressed
if (isset($form_data['buttonSave']))
    {
    # save $store into db
    $store->save();
    #get actual logo list for store
    $store->getLogosList();
    #set flag 'store is saved'
    $message = 'saved';
    }
}

# display store-info page
echo $twig->render('Stores/store-info.html.twig', [
    'store' => $store,
    'message' => $message,
    'new_logo' => $new_logo,
    'url' => getPath('image'),
    ]);
