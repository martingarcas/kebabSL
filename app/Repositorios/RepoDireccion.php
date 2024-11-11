<?php


	namespace App\Repositorios;


	use App\Models\Direccion;

	class RepoDireccion {

		private $con;

		public function __construct($con) {

			$this->con = $con;
		}

		// Método para acceder a la conexión
		public function getConnection() {
			return $this->con;
		}

		public function create(Direccion $direccion, $usuarioId) {

			$calle 	= $direccion->getCalle();
			$numero = $direccion->getNumero();
			$activa = $direccion->getActiva();


			$stm = $this->con->prepare("INSERT INTO direccion (calle, numero, activa, usuario_id) VALUES (:calle, :numero, :activa, :usuarioId)");
			$stm->execute(['calle' => $calle, 'numero' => $numero, 'activa' => $activa, 'usuarioId' => $usuarioId]);

			return $direccion;
		}

	}

?>