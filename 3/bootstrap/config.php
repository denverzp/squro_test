<?php
//http
define('HTTP_SERVER', 'http://' . $_SERVER['HTTP_HOST'] . '/');
        
//paths
define('DIR_ROOT', __DIR__ . '/../');
define('DIR_APPLICATION', DIR_ROOT . '/App/');
define('DIR_TEMPLATE', DIR_ROOT . 'App/View/');
define('DIR_DATABASE', DIR_ROOT . 'database/');
define('DIR_LOGS', DIR_ROOT . 'logs/');

//database settings
define('DB_DATABASE', 'database.sqlite');
