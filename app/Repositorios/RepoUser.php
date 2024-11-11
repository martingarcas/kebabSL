<?php


	namespace App\Repositorios;


	use App\Models\Usuario;

	class RepoUser {

		private $con;

		public function __construct($con) {

			$this->con = $con;
		}

		// Método para acceder a la conexión
		public function getConnection() {
			return $this->con;
		}

		public function create(Usuario $user) {

			$nombre 		= $user->getNombre();
			$email 			= $user->getEmail();
			$contrasenna 	= $user->getContrasenna();
			$dni 			= $user->getDni();


			$stm = $this->con->prepare("INSERT INTO usuario (nombre, email, dni, contrasenna) VALUES (:nombre, :email, :dni, :contrasenna)");
			$stm->execute(['nombre' => $nombre, 'email' => $email, 'dni' => $dni, 'contrasenna' => $contrasenna]);

			// Obtener el ID del usuario recién insertado
			$user->setId($this->con->lastInsertId());

			return $user;
		}

	}

?>