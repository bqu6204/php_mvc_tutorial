<?php

require_once __DIR__.'/vendor/autoload.php';
use app\core\Application;
use app\core\Router;

$app = new Application();
$router = new Router();

$app->run();

$app->$router->get('/',function(){
    return 'Hello World!';
});

$app->$router->get('/contact',function(){
    return 'Contact me !';
});

