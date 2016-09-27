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
            $email = $params['customer']->getEmail();
            break;

        case 'store_verification':
            $subject = 'Verify store ' . $params['store']->getName() . ' ownership';
            $email = $params['store']->getEmail();
            break;
        
        case 'store_rm_verification':
            $subject = 'Verify store ' . $params['store']->getName() . ' removing';
            $email = $params['store']->getEmail();
            break;

        case 'password_recovery':
            $subject = 'Password recovery for account at ' . SIGNUP_SERVER;
            $email = $params['customer']->getEmail();
            break;
        
        default:
            $subject = '';
            $message = '';
            $email = '';
            break;
    }

    $message = getEmailMessage($template, $params);
    
    ini_set("SMTP", SMTP_SERVER);
    ini_set("smtp_port", SMTP_PORT);
    $additional_headers = "From: " . MAIL_FROM . "\r\n";
    mail($email, $subject, $message, $additional_headers);

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
    $template_params = [$params, 
        'server' => SIGNUP_SERVER,
        'validation_link' => getPath($template, $params),
        ];

    return getTwig()->render("Email/${template}.html.twig", $template_params);
}
