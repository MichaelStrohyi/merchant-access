<?php

require_once __DIR__  . '/../../include/core.php';
require_once INCLUDE_DIR  . 'customers.php';

$customer = getLoggedCustomer();
$store = new App\Store($customer);

$form_data = filter_input(INPUT_POST, 'store', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

if (!empty($form_data)) {
    # steps to do when form was submitted


    # if button Cancel is pressed
    if (isset($form_data['buttonCancel'])) {
        # redirect customer to stores.php
        redirectToPage('stores');
        exit;
    }

    $store
        ->fetchInfo($form_data)
        ->validate()
        ;
   
    if ($store->isValid()) {
        # save store data into db
        if (!$store->save()) {
            echo $twig->render('Signup/db-access-error.html.twig', [
                    'message' => 'Some arror happens. Please, try again later.',
                    ]);
            exit;
        }

        # send email to confirm store adding
        emailConfirmation('store_verification', ['store' => $store]);
        
        # display success store adding page
        echo $twig->render('Stores/success-store-add.html.twig', [
            'store' => $store,
            ]);
        exit;
    }
}

# display store add page with errors if any exist}

echo $twig->render('Stores/store-add.html.twig', [
    'store' => $store
    ]);
