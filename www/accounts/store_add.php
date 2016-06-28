<?php

require_once __DIR__  . '/../../include/core.php';

$customer = getLoggedCustomer();

echo $twig->render('Panel/store_add.html.twig', []);
