<?php

define('LOGIN_CHECK', false);

require_once __DIR__  . '/../include/core.php';
require_once INCLUDE_DIR  . 'customers.php';

$customer = new App\Customer;
$googleCaptcha = new App\GoogleCaptcha(GOOGLE_CAPTCHA_SITEKEY, GOOGLE_CAPTCHA_SECRETKEY);

if (isset($_POST['buttonSubmit'])) {
    # get customers registration data	
    $customer
        ->fetchInfo($_POST['customer'])
        ->validate()
        ;
    $googleCaptcha
        ->fetchInfo($_POST)
        ->validate()
        ;

    if ($customer->isValid() && $googleCaptcha->isValid()) {
        # save customer registration data into db
        if (!$customer->save()) {
            echo $twig->render('Signup/db-access-error.html.twig', [
                    'customer' => $customer,
                    'message' => 'Some arror happens. Please, try again later.',
                    ]);
            exit;
        }

        # send email to confirm registration
        emailConfirmation('account_verification', ['customer' => $customer]);
        
        # display success registration page
        echo $twig->render('Signup/success-registration.html.twig', [
            'customer' => $customer,
            'action' => 'registered',
            ]);
        exit;
    }
}

# display registration page with errors if any exist
echo $twig->render('Signup/registration.html.twig', [
    'customer' => $customer,
    'captcha' => $googleCaptcha,
    ]);
