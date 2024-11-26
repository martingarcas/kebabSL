<?php


	namespace App\Repositorios;

	use App\Models\Usuario;
	use PDO;

	class RepoUser {

		public function create(Usuario $user) {

			$con = Conexion::getConection();

			$nombre 		= $user->getNombre();
			$email 			= $user->getEmail();
			$contrasenna 	= $user->getContrasenna();
			$dni 			= $user->getDni();
			$rol 			= $user->getRol();


			$stm = $con->prepare(
				"INSERT INTO usuario (nombre, email, dni, contrasenna, rol) VALUES (:nombre, :email, :dni, :contrasenna, :rol)"
			);

			$stm->execute([
				'nombre' 		=> $nombre,
				'email' 		=> $email,
				'dni' 			=> $dni,
				'contrasenna' 	=> $contrasenna,
				'rol' 			=> $rol
			]);

			// Obtener el ID del usuario recién insertado
			$user->setId($con->lastInsertId());

			return $user;
		}

		public function getById($id) {

			$con = Conexion::getConection();

			$stm = $con->prepare(
				"SELECT * FROM usuario WHERE id = :id"
			);

			$stm->execute([
				'id' => $id
			]);

			// Obtener el resultado como un array asociativo
			$data = $stm->fetch(PDO::FETCH_ASSOC);

			if ($data) {
				// Crear un objeto Usuario con los datos obtenidos
				$usuario = new Usuario(
					$data['nombre'],
					$data['apellido1'],
					$data['apellido2'],
					$data['contrasenna'],
					$data['telefono'],
					$data['email'],
					$data['dni'],
					$data['foto'],
					$data['monedero'],
					$data['carrito'],
					$data['rol']
				);

				return $usuario;
			}

			return null;
		}

		// Método para verificar si existe un usuario por un campo genérico
		public function existePorCampo($campo, $valor) {

			$con = Conexion::getConection();
			$stm = $con->prepare("SELECT COUNT(*) FROM usuario WHERE $campo = :valor");
			$stm->execute(['valor' => $valor]);

			return $stm->fetchColumn() > 0;
		}

		public function findByEmail($email) {

			$con = Conexion::getConection();

			// Preparar la consulta para buscar por el email
			$stm = $con->prepare("SELECT * FROM usuario WHERE email = :email");
			$stm->execute(['email' => $email]);

			// Obtener el resultado como un array asociativo
			$response = $stm->fetch(PDO::FETCH_ASSOC);

			if ($response) {
				// Crear un objeto Usuario con los datos obtenidos
				$usuario = new Usuario(
					$response['nombre'],
					$response['apellido1'],
					$response['apellido2'],
					$response['contrasenna'],
					$response['telefono'],
					$response['email'],
					$response['dni'],
					$response['foto'],
					$response['monedero'],
					$response['carrito'],
					$response['rol']
				);

				return $usuario;
			}

			// Si no se encuentra el usuario, devolver null
			return null;
		}

		public function actualizarCampo($idUsuario, $campo, $valor) {
			// Conexión a la base de datos
			$con = Conexion::getConection();

			// Preparamos y ejecutamos la consulta
			$stm = $con->prepare("UPDATE usuario SET $campo = :valor WHERE id = :id");

			// Ejecutamos la consulta
			$stm->execute([
				'valor' => $valor,
				'id'    => $idUsuario,
			]);

			return $stm->rowCount() > 0; // Retorna true si se actualizó algo
		}

	}

?>