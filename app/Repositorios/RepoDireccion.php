<?php


	namespace App\Repositorios;


	use App\Models\Direccion;

	class RepoDireccion {

		public function create(Direccion $direccion, $usuarioId) {

			$con = Conexion::getConection();

			$calle 	= $direccion->getCalle();
			$numero = $direccion->getNumero();
			$activa = $direccion->getActiva();


			$stm = $con->prepare(
				"INSERT INTO direccion (calle, numero, activa, usuario_id) VALUES (:calle, :numero, :activa, :usuarioId)"
			);

			$stm->execute([
				'calle' => $calle,
				'numero' => $numero,
				'activa' => $activa,
				'usuarioId' => $usuarioId]
			);

			return $direccion;
		}

	}

?>