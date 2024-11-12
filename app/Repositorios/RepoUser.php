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


			$stm = $con->prepare(
				"INSERT INTO usuario (nombre, email, dni, contrasenna) VALUES (:nombre, :email, :dni, :contrasenna)"
			);
			$stm->execute([
				'nombre' 		=> $nombre,
				'email' 		=> $email,
				'dni' 			=> $dni,
				'contrasenna' 	=> $contrasenna
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
					$data['carrito']
				);

				return $usuario;
			}

			return null;
		}

		public function existeUsuario($email, $dni) {
			// Obtener la conexión a la base de datos
			$con = Conexion::getConection();

			// Preparar la consulta SQL para buscar por email o DNI
			$stm = $con->prepare(
				"SELECT COUNT(*) FROM usuario WHERE email = :email OR dni = :dni"
			);

			// Ejecutar la consulta pasando los parámetros de email y dni
			$stm->execute([
				'email' => $email,
				'dni' => $dni
			]);

			// Obtener el resultado de la consulta (el número de filas encontradas)
			$result = $stm->fetchColumn();

			// Si hay al menos una fila, significa que el usuario existe
			if ($result > 0) {
				return true;
			}

			return false;
		}


	}

?>