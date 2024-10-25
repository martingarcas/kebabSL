<?php
	require './Miautocargador.php'; // Asegúrate de que la ruta a este archivo sea correcta
	require '../vendor/autoload.php'; // También verifica esta ruta

	// Cargar las rutas
	$routes = require '../routes/routes.php'; // Asegúrate de que esta ruta sea correcta

	// Obtener el valor de 'menu' de la URL, o usar la ruta por defecto
	$path = isset($_GET['menu']) ? $_GET['menu'] : '';

	// Determinar el controlador y el método a utilizar
	$controllerMethod = $routes[$path] ?? [App\Controllers\HomeController::class, 'index'];

	// Separar el controlador y el método
	list($controllerClass, $method) = $controllerMethod;

	// Crear una instancia del controlador y llamar al método
	$controller = new $controllerClass();
	$controller->$method();

?>