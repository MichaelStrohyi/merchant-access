<?php

require_once __DIR__  . '/../../include/core.php';
require_once INCLUDE_DIR  . 'customers.php';

$customer = getLoggedCustomer();
$store = new App\Store($customer);

$form_data = filter_input(INPUT_POST, 'store', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

if (!empty($form_data)) {
    # steps to do when form was submitted
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
        EmailConfirmation('store_verification', ['store' => $store]);
        
        # display success registration page
        echo $twig->render('Panel/success-store-add.html.twig', [
            'store' => $store,
            ]);
        exit;
    }
}

# display registration page with errors if any exist}

echo $twig->render('Panel/store-add.html.twig', [
    'store' => $store
    ]);
