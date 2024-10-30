<?php
	require_once './Miautocargador.php';
	require_once '../vendor/autoload.php';

	// Cargar las rutas
	$routes = require_once '../routes/routes.php';

	// Obtener el valor de 'menu' de la URL a través del $_GET, o usar la ruta por defecto
	$path = isset($_GET['menu']) ? $_GET['menu'] : '';

	// Determinar el controlador y el método a utilizar
	$controllerMethod = $routes[$path] ?? [App\Controllers\HomeController::class, 'index', 'home'];

	// Separar el controlador y el método obtenidos del routes
	list($controllerClass, $method, $view) = $controllerMethod;

	// Crear una instancia del controlador y llamar al método
	$controller = new $controllerClass();
	$controller->$method($view);

?>