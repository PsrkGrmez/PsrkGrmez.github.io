<?php
require 'vendor/autoload.php';

use FastRoute\RouteCollector;
// تعریف مسیرها
$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    $r->addRoute('GET', '/', 'homeHandler');
    $r->addRoute('GET', '/about', 'aboutHandler');
    $r->addRoute('GET', '/user/{id:\d+}', 'userHandler');
});

// دریافت متد و آدرس درخواست
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// حذف query string از آدرس
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo '404 Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        // ... 405 Method Not Allowed
        $allowedMethods = $routeInfo[1];
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // ... call $handler with $vars
        call_user_func($handler, $vars);
        break;
}

// تعریف هندلرها
function homeHandler() {
    echo 'Welcome to the homepage!';
}

function aboutHandler() {
    echo 'This is the about page.';
}

function userHandler($vars) {
    echo 'User ID: ' . $vars['id'];
}
