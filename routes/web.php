<?php

use App\Controller\ReservationController;
use App\Controller\SalleController;
use FastRoute\RouteCollector;

return FastRoute\simpleDispatcher(function (RouteCollector $router): void {
	$router->addRoute('GET', '/', [SalleController::class, 'index']);
	$router->addRoute('GET', '/salles', [SalleController::class, 'index']);
	$router->addRoute('GET', '/salles/create', [SalleController::class, 'create']);
	$router->addRoute('POST', '/salles', [SalleController::class, 'store']);
	$router->addRoute('POST', '/salles/store', [SalleController::class, 'store']);
	$router->addRoute('GET', '/salles/{id:\d+}', [SalleController::class, 'show']);
	$router->addRoute('GET', '/salles/{id:\d+}/edit', [SalleController::class, 'edit']);
	$router->addRoute('POST', '/salles/{id:\d+}/edit', [SalleController::class, 'update']);
	$router->addRoute('GET', '/reservations', [ReservationController::class, 'index']);
	$router->addRoute('GET', '/reservations/create', [ReservationController::class, 'create']);
	$router->addRoute('POST', '/reservations', [ReservationController::class, 'store']);
	$router->addRoute('POST', '/reservations/store', [ReservationController::class, 'store']);
	$router->addRoute('GET', '/reservations/{id:\d+}', [ReservationController::class, 'show']);
	$router->addRoute('POST', '/reservations/{id:\d+}/cancel', [ReservationController::class, 'cancel']);
});
