<?php

require_once __DIR__  . '/../../../include/core.php';

# get variables from $_GET
$ticket_id = filter_input(INPUT_GET, 'id');

# get variables from $_POST
$form_data = filter_input(INPUT_POST, 'ticket', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

# get ticket_id from $_POST if it is set
if (isset($form_data['id'])) {
    $ticket_id = $form_data['id'];
}

# unset flag 'message is added'
$message = '';

# check if current $customer has logged in
$customer = getLoggedCustomer();

$ticket = new App\Ticket($customer, $ticket_id);

# check if current $customer has access to $ticket
$access_error = $customer->checkAccess(['ticket' => $ticket]);
if (!empty($access_error)) {
    showErrorPage(['ticket' => $ticket], $access_error);
    exit;
}

# if button Cancel is pressed
if (isset($form_data['buttonCancel'])) {
    # redirect customer to tickets page
    redirectToPage('tickets');
    exit;
}

# if button Save is pressed
if (isset($form_data['buttonSubmit']))
    {
    # grab data from $form_data to $ticket vars

    $ticket
        ->fetchInfo($form_data)
        ->validate()
        ;
    # set userId for new message
    if (isset($form_data['messages']['new'])) {
        $ticket->getLastMessage()->setUserId($customer->getId());
    }

   if ($ticket->isValid()) {
        # save ticket data into db
        if (!$ticket->save()) {
            echo $twig->render('Signup/db-access-error.html.twig', [
                    'message' => 'Some arror happens. Please, try again later.',
                    ]);
            exit;
        }

    #set flag 'message is added'
    $alert = 'saved';
    }
}

# display ticket-info page
echo $twig->render('Tickets/ticket-info.html.twig', [
    'ticket' => $ticket,
    'alert' => $alert,
    ]);
