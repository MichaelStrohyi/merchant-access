<?php

/**
 * Return url for requested page ($route_name)
 *
 * @param string $route_name
 * @param array $params
 * @return string
 * @author Michael Strohyi
 **/
function getPath($route_name, $params = [])
{
    switch ($route_name) {
        case 'main':
            $location = '/accounts/main.php';
            break;

        case 'login':
            $location = '/accounts/login.php';
            break;

        case 'registration':
            $location = '/registration.php';
            break;
            
        case 'account_verification':
            $customer = $params['customer'];
            $location = '/accounts/verify.php?id=' . $customer->getId() . '&hash=' . $customer->getHash();
            break;

        case 'store_verification':
            $store = $params['store'];
            $location = '/accounts/store_verify.php?store=' . $store->getId() . '&action=add&' . '&id=' . $store->getCustomer()->getId() . '&hash=' . $store->getHash();
            break;

        case 'store_rm_verification':
            $store = $params['store'];
            $location = '/accounts/store_verify.php?store=' . $store->getId() . '&action=rm&' . '&id=' . $store->getCustomer()->getId() . '&hash=' . $store->getHash();
            break;

        case 'stores':
            $location = '/accounts/stores.php';
            break;

        case 'resend_verification':
            $store = $params['store'];
            $location = '/accounts/send_store_verification.php?store=' . $store->getId() . '&action=add';
            break;

        case 'resend_rm_verification':
            $store = $params['store'];
            $location = '/accounts/send_store_verification.php?store=' . $store->getId() . '&action=rm';
            break;

        case 'login':
            $location = '/accounts/login.php';
            break;

        default:
            $location = null;
            break;
    }

    return $location;
}

/**
 * Make redirect to requested page ($route_name)
 *
 * @return void
 * @author Michael Strohyi
 **/
function redirectToPage($route_name, $params = [])
{
    $location = getPath($route_name, $params = []);

    if (empty($location)) {
        throw Exception('Page not found');
    }

    header("Location: $location");
    exit;
}