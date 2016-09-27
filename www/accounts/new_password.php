<?php

define('LOGIN_CHECK', false);

require_once __DIR__  . '/../../include/core.php';
require_once INCLUDE_DIR  . 'customers.php';

$id = filter_input(INPUT_GET, 'id');
$hash = filter_input(INPUT_GET, 'hash');

$customer = new App\Customer($id);

if (!$customer->exists() || !$customer->isHashValid($hash)) {
    # show error page with "broken validation link" message
    echo $twig->render('Signup/link-error.html.twig', [
        'url' => getPath('login')
        ]);
    exit;
}

if (!$customer->isActive()) {
    # show error page with "your account is not active" message
    echo $twig->render('Panel/customer-not-active.html.twig', [
        'url' => getPath('login')
        ]);
    exit;
}


if (isset($_POST['buttonSubmit'])) {
    # get customers registration data   
    $customer
        ->fetchInfo($_POST['customer'])
        ->validate()
        ;

    if ($customer->isValid()) {
        # save customer registration data into db
        if (!$customer->save()) {
            echo $twig->render('Signup/db-access-error.html.twig', [
                    'customer' => $customer,
                    'message' => 'Some arror happens. Please, try again later.',
                    ]);
            exit;
        }

        # display success registration page
        echo $twig->render('Panel/success-new-password.html.twig', [
            'customer' => $customer,
            'url' => getPath('login'),
            ]);
        exit;
    }
}

# show page with form for new password with errors if any exist
echo $twig->render('Panel/new-password.html.twig', [
    'customer' => $customer,
    'url' => getPath('login'),
    ]);
