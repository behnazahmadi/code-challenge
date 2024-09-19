<?php

require_once '../vendor/autoload.php';

use FastRoute\RouteCollector;
use app\controllers\MenuController;

$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    $r->addRoute('GET', '/', function() {
        echo 'welcome to the home page!';
    });

    $r->addRoute('GET', '/admin/menu', [MenuController::class, 'index']);
    $r->addRoute('POST', '/admin/menu', [MenuController::class, 'store']);
    $r->addRoute('GET', '/menu', [MenuController::class, 'index']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo '404 - Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo '405 - Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        if (is_array($handler)) {
            [$controller, $method] = $handler;
            call_user_func_array([new $controller, $method], $vars);
        } else {
            call_user_func($handler);
        }
        break;
}
