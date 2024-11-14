<?php


	namespace App\Repositorios;


	use App\Models\Direccion;

	class RepoDireccion {

		public function create(Direccion $direccion, $usuarioId) {

			$con = Conexion::getConection();

			$localidad 	= $direccion->getLocalidad();
			$calle 		= $direccion->getCalle();
			$numero 	= $direccion->getNumero();
			$activa 	= $direccion->getActiva();


			$stm = $con->prepare(
				"INSERT INTO direccion (localidad, calle, numero, activa, usuario_id) VALUES (:localidad, :calle, :numero, :activa, :usuarioId)"
			);

			$stm->execute([
				'localidad' => $localidad,
				'calle' 	=> $calle,
				'numero' 	=> $numero,
				'activa' 	=> $activa,
				'usuarioId' => $usuarioId
			]);

			return $direccion;
		}

	}

?>