<?php


	namespace App\Repositorios;


	use App\Models\Direccion;

	class RepoDireccion {

		private $con;

		public function __construct($con) {

			$this->con = $con;
		}

		public function create(Direccion $direccion) {

			$calle 	= $direccion->getCalle();
			$numero = $direccion->getNumero();
			$activa = $direccion->getActiva();


			$stm = $this->con->prepare("INSERT INTO direccion (calle, numero, activa) VALUES (:calle, :numero, :activa)");
			$stm->execute(['calle' => $calle, 'numero' => $numero, 'activa' => $activa]);

			return $direccion;
		}

	}

?>