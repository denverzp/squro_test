<?php

//config
require_once __DIR__ . '/config.php';

//Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

//error handlers
require_once __DIR__ . '/handlers.php';

//helpers
require_once DIR_APPLICATION . 'Engine/helpers/utf8.php';

//Registry
$registry = new \App\Engine\Registry();

//Request
$request = new \App\Engine\Request();
$registry->set('request', $request);

//Log
$log = new \App\Engine\Log();
$registry->set('log', $log);

//DB
$db = new \App\Engine\DB(DB_DATABASE);
$registry->set('db', $db);

//Front
$front = new \App\Engine\Front($registry);

//Routing
$route = new \App\Engine\Route($registry);

//Action
$action = new \App\Engine\Action($route->getRoute());

//Dispatch
$front->execute($action);
