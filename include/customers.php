<?php

/**
 * Send the confirmation link to customer's email
 *
 * @param App\Customer $customer
 * @return void
 * @author Michael Strohyi
 **/
function customerEmailConfirmation($customer)
{
    $subject = 'Verify your registration at ' . SIGNUP_SERVER;
    $message = getEmailMessage('account_verification', $customer);
    if (!(defined('ENVIRONMENT') && ENVIRONMENT == 'development')) {
        mail($customer->getEmail(), $subject, $message);
    }
}

/**
 * Return text of email according to set $template
 *
 * @param string $template
 * @param App\Customer $customer
 * @return string
 * @author Michael Strohyi
 **/
function getEmailMessage($template, $customer)
{
    return getTwig()->render("Email/${template}.html.twig", [
            'customer' => $customer,
            'server' => SIGNUP_SERVER,
            'validation_link' => $customer->getValidationLink(),
            ]);
}
