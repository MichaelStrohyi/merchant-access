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
    # if new logo is set

    if (!empty($_FILES['new_logo']['name']) && $_FILES['new_logo']['error'] == 0) {
        $new_logo->gatherFileInfo($_FILES['new_logo']['tmp_name'], $_FILES['new_logo']['size']);
        $new_logo->validate();

        if (!$new_logo->isValid()) {
            echo $twig->render('Stores/store-info.html.twig', [
                'store' => $store,
                'message' => $message,
                'new_logo' => $new_logo,
                ]);
            exit;
        }
        
        # need to save new_logo into db
        # ....
        # ....
        # ....
    }

    # save $store into db
    $store->save();

    $message = 'saved';
}

# display store-info page
echo $twig->render('Stores/store-info.html.twig', [
    'store' => $store,
    'message' => $message,
    'new_logo' => $new_logo,
    ]);
