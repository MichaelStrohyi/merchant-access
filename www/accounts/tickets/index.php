<?php

require_once __DIR__  . '/../../../include/core.php';

#get customer from session
$customer = getLoggedCustomer();

#get list of customer's tickets
$tickets = $customer->getTickets();

#render page with list of customer's stores
$routes = ['ticket_info' => getPath('ticket_info'),
    'ticket_close' => getPath('ticket_close'),
    'ticket_add' => getPath('ticket_add'),
    ];

//!!! stub

$routes = ['ticket_info' => '/accounts/tickets/ticket_info.php?id=',
    'ticket_close' => '/accounts/tickets/ticket_close.php?id=',
    'ticket_add' => '/accounts/tickets/ticket_add.php',
    ];
    
//!!! end of stub


echo $twig->render('Tickets/tickets.html.twig', [
    'customer' => $customer,
    'routes' => $routes,
    ]);
