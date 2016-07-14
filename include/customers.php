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

        case 'store_verification':
            $subject = 'Verify store ' . $params['store']->getName() . ' ownership';
            $email = $params['store']->getEmail();
            break;
        
        case 'store_rm_verification':
            $subject = 'Verify store ' . $params['store']->getName() . ' removing';
            $email = $params['store']->getEmail();
            break;
        
        default:
            $subject = '';
            $message = '';
            $email = '';
            break;
    }

    $message = getEmailMessage($template, $params);
    
    if (!(defined('ENVIRONMENT') && ENVIRONMENT == 'development')) {
        mail($email, $subject, $message);
    } else {
        echo '<table border=3><tr><td bgcolor="yellow">' . "$subject <br /> $message </tr></td></table>";
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
    $template_params = [$params, 
        'server' => SIGNUP_SERVER,
        'validation_link' => getPath($template, $params),
        ];

    return getTwig()->render("Email/${template}.html.twig", $template_params);
}
