<?php


	namespace App\Repositorios;


	use App\Models\Usuario;

	class RepoUser {

		private $con;

		public function __construct($con) {

			$this->con = $con;
		}

		public function create(Usuario $user) {

			$nombre = $user->getNombre();
			$email 	= $user->getEmail();
			$dni 	= $user->getDni();


			$stm = $this->con->prepare("INSERT INTO usuario (nombre, email, dni) VALUES (:nombre, :email, :dni)");
			$stm->execute(['nombre' => $nombre, 'email' => $email, 'dni' => $dni]);

			return $user;
		}

	}

?>