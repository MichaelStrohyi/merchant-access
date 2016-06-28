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
            $location = '/accounts/verify.php?store=' . $store->getId() . '&hash=' . $store->getHash();
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