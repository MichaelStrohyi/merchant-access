<?php

/**
 * Send the confirmation link to customer's email
 *
 * @param array $params
 * @return void
 * @author Michael Strohyi
 **/
function emailConfirmation($template, $params = [])
{
    switch ($template) {
        case 'account_verification':
            $subject = 'Verify your registration at ' . SIGNUP_SERVER;
            $message = getEmailMessage($template, $params);
            break;

        case 'store_verification':
            $subject = 'Verify store ' . $params['store']->getName() . ' ownership';
            $message = getEmailMessage($template, $params);
            break;
        
        default:
            $subject = '';
            $message = '';
            break;
    }
    echo "$subject <br> $message";
    if (!(defined('ENVIRONMENT') && ENVIRONMENT == 'development')) {
        mail($customer->getEmail(), $subject, $message);
    }
}

/**
 * Return text of email according to set $template
 *
 * @param string $template
 * @param array $params
 * @return string
 * @author Michael Strohyi
 **/
function getEmailMessage($template, $params = [])
{
    switch ($template) {
        case 'account_verification':
            $template_params = [
                'customer' => $params['customer'],
                'server' => SIGNUP_SERVER,
                'validation_link' => getPath($template, ['customer' => $params['customer']]),
                ];
            break;

        case 'store_verification':
            $template_params = [
                'store' => $params['store'],
                'server' => SIGNUP_SERVER,
                'validation_link' => getPath($template, ['store' => $params['store']]),
                ];
            break;

        default:
            $template_params = [];
            break;
    }
    return getTwig()->render("Email/${template}.html.twig", $template_params);
}
