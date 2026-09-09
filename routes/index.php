<?php


$dispatcher = require_once dirname(__DIR__) . '/routes/web.php';

$httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        require_once dirname(__DIR__) . '/templates/error/404.php';
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        header('Allow: ' . implode(', ', $routeInfo[1]));
        require_once dirname(__DIR__) . '/templates/error/405.php';
        break;

    case FastRoute\Dispatcher::FOUND:
        [$handler, $vars] = [$routeInfo[1], $routeInfo[2]];
        [$controllerClass, $method] = $handler;
        $controller = $container->get($controllerClass);
        $controller->{$method}(...array_values($vars));
        break;
}
