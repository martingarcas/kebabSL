<?php

	namespace App\Controllers;

	use App\Api\ApiUser;
	use League\Plates\Engine;

	class RegisterController {

		protected $templates;

		public function __construct() {
			$this->templates = new Engine('../resources/views');
		}

		// Este es el método que maneja el formulario de registro
		public function createUser() {
			// Verificar si el formulario ha sido enviado
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {

				// Obtener los datos del formulario
				$data = [
					'nombre' 		=> $_POST['nombre'] ?? '',
					'apellido1' 	=> $_POST['apellido1'] ?? null,
					'apellido2' 	=> $_POST['apellido2'] ?? null,
					'email' 		=> $_POST['email'] ?? '',
					'dni' 			=> $_POST['dni'] ?? '',
					'calle' 		=> $_POST['calle'] ?? '',
					'numero' 		=> $_POST['numero'] ?? '',
					'contrasenna' 	=> $_POST['contrasenna'],
					'telefono' 		=> $_POST['telefono'] ?? null,
					'foto' 			=> $_POST['foto'] ?? null,
					'monedero' 		=> $_POST['monedero'] ?? null,
					'carrito' 		=> $_POST['carrito'] ?? null,
					'activa' 		=> 1 // Suponemos que la dirección está activa por defecto
				];

				// Validar campos obligatorios
				$errores = [];

				// Validar que el nombre no esté vacío
				if (empty($data['nombre'])) {
					$errores[] = "El nombre es obligatorio.";
				}

				// Validar que la contraseña no esté vacía
				if (empty($data['contrasenna'])) {
					$errores[] = "La contraseña es obligatoria.";
				}

				// Validar que el DNI no esté vacío
				if (empty($data['dni'])) {
					$errores[] = "El DNI es obligatorio.";
				}

				// Validar que el email no esté vacío
				if (empty($data['email'])) {

					$errores[] = "El correo electrónico es obligatorio.";

				} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
					$errores[] = "El correo electrónico no es válido.";
				}

				// Validar que la calle no esté vacía
				if (empty($data['calle'])) {
					$errores[] = "La calle es obligatoria.";
				}

				// Validar que el número no esté vacío
				if (empty($data['numero'])) {

					$errores[] = "El número es obligatorio.";

				} elseif (!is_numeric($data['numero'])) {

					$errores[] = "El número debe ser un valor numérico.";
				}

				// Si hay errores, devolverlos
				if (count($errores) > 0) {
					// Renderizar la vista con los errores
					echo $this->templates->render('register', [
						'errores' => $errores,
						'data' => $data // Pasar los datos para mantenerlos en los campos
					]);
					return; // Detener la ejecución
				}

				// Cifrar la contraseña antes de almacenarla
				$data['contrasenna'] = password_hash($data['contrasenna'], PASSWORD_BCRYPT); // si luego no funciona fuera.

				// Crear una instancia de la API para gestionar el registro
				$apiUser = new ApiUser();

				// Llamar al método de la API para crear el usuario
				$response = $apiUser->crearUsuario($data);

				// Manejar la respuesta: si es éxito, mostrar la vista de éxito
				if ($response['status'] === 'success') {
					// Cambiar el código de estado HTTP
//					http_response_code(201); // Se creó el usuario correctamente

					// Redirigir a la página de éxito
					header('Location: /?success=true');
					exit; // Es importante terminar el script después de la redirección
				} else {
					// Si hay un error, devolver el mensaje de error con código 422
//					http_response_code(422); // Error en los datos, por ejemplo, el correo ya existe
					echo "Error: " . $response['message'];
				}
			}
		}

		public function index($view) {
			echo $this->templates->render($view);
		}
	}

?>