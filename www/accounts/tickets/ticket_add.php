<?php

require_once __DIR__  . '/../../../include/core.php';
require_once INCLUDE_DIR  . 'customers.php';

$customer = getLoggedCustomer();
$ticket = new App\Ticket($customer);

$form_data = filter_input(INPUT_POST, 'ticket', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

if (!empty($form_data)) {
    # steps to do when form was submitted

    # if button Cancel is pressed
    if (isset($form_data['buttonCancel'])) {
        # redirect customer to tickets.php
        redirectToPage('tickets');
        exit;
    }

    $ticket
        ->fetchInfo($form_data)
        ->validate()
        ->getLastMessage()->setUserId($customer->getId())
        ;

    if ($ticket->isValid()) {
        # save ticket data into db
        if (!$ticket->save()) {
            echo $twig->render('Signup/db-access-error.html.twig', [
                    'message' => 'Some arror happens. Please, try again later.',
                    ]);
            exit;
        }

        # send email with link to view ticket
        emailConfirmation('ticket_added', ['ticket' => $ticket,
            'customer' => $customer,
            ]);

        # display success ticket adding page
        echo $twig->render('Tickets/success-ticket-add.html.twig', [
            'ticket' => $ticket,
            'url' => getPath('tickets'),
            'ticket_info' => getPath('ticket_info'),
            ]);
        exit;
    }
}

# display ticket add page with errors if any exist}
echo $twig->render('Tickets/ticket-add.html.twig', [
    'ticket' => $ticket,
    ]);
