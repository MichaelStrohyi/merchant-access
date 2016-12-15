<?php

defined("ROOT_DIR") || define("ROOT_DIR", __DIR__ . '/../');
defined("INCLUDE_DIR") || define("INCLUDE_DIR", ROOT_DIR . 'include/');
defined("TWIG_TEMPLATES_DIR") || define("TWIG_TEMPLATES_DIR", ROOT_DIR . 'templates/');
defined("WEB_DIR") || define("WEB_DIR", ROOT_DIR . 'www/');
defined("DEVELOPMENT_MODE") || define("DEVELOPMENT_MODE", "development");
defined("ENVIRONMENT") || define("ENVIRONMENT", getenv("APPLICATION_ENV"));