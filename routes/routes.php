<?php

use App\Api\ApiRegister;
use App\Controllers\HomeController;
use App\Controllers\AuthController;

$routes = [
	'' 				=> [HomeController::class, 'index', 'home'],
	'login' 		=> [AuthController::class, 'showLoginForm', 'login'],
	'login-post' 	=> [AuthController::class, 'loginUser', 'home'],
	'register' 		=> [AuthController::class, 'showRegisterForm', 'register'],
	'register-post' => [AuthController::class, 'registerUser', 'home'],
	'logout' 		=> [AuthController::class, 'logoutUser', 'home'],
	'apiRegister'   => [ApiRegister::class, 'handleRequest', null],
];

return $routes;

?>
