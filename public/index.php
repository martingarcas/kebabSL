<?php
	require_once './Miautocargador.php';
	require_once '../vendor/autoload.php';

	// Cargar las rutas
	$routes = require_once '../routes/routes.php';

	// Obtener el valor de 'menu' de la URL a través del $_GET, o usar la ruta por defecto
	// $path = isset($_GET['menu']) ? $_GET['menu'] : '';
	// Esta línea utiliza la función parse_url() para extraer solo la parte del "path" de la URL, ignorando cualquier parámetro de consulta.
	$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

	// De esta manera aseguro que tampoco se puede acceder a kebabsl.com/index.php, sólo se accede a través de la ruta principal
	if (array_key_exists($path, $routes)) {
		$controllerMethod = $routes[$path];

	} else {
		// Si la ruta no existe, devuelve un error 404
		header("HTTP/1.0 404 Not Found");
		echo "404 Not Found"; // Aquí incluir vista personalizada
		exit;
	}

	// Determinar el controlador y el método a utilizar
	// $controllerMethod = $routes[$path] ?? [App\Controllers\HomeController::class, 'index', 'home'];

	// Separar el controlador y el método obtenidos del routes
	list($controllerClass, $method, $view) = $controllerMethod;

	// Crear una instancia del controlador y llamada al método.
	$controller = new $controllerClass();
	$controller->$method($view);

?>