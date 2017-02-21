<?php

require_once __DIR__  . '/../../../include/core.php';
require_once INCLUDE_DIR  . 'customers.php';

$ticket_id = filter_input(INPUT_GET, 'id');
$button_submit = filter_input(INPUT_POST, 'buttonSubmit');
$button_cancel = filter_input(INPUT_POST, 'buttonCancel');

if (isset($button_cancel)) {
    redirectToPage('tickets');
    exit;
}

$customer = getLoggedCustomer();

$ticket = new App\Ticket($customer, $ticket_id);

$access_error = $customer->checkAccess(['ticket' => $ticket]);

if (!empty($access_error)) {
    showErrorPage(['ticket' => $ticket], $access_error);
    exit;
}

if (isset($button_submit)) {

    if (!$ticket->closeTicket()) {
            # show db access error page
             echo $twig->render('Signup/db-access-error.html.twig', [
                'message' => 'Some arror happens. Please, try again later.'
                ]);
            exit;
        }


    # display success ticket closing page
    echo $twig->render('Tickets/success-ticket-close.html.twig', [
        'ticket' => $ticket,
        'url' => getPath('tickets'),
        ]);
    exit;
}

echo $twig->render('Tickets/ticket-close.html.twig', [
    'ticket' => $ticket,
    ]);

