<?php

require_once __DIR__  . '/../../include/core.php';
require_once __DIR__  . '/../../include/routes.php';

echo 'Hello ' . $customer->getName() . '<br> You can see list of your stores <a href="' . getPath('stores') . '">here</a>';

