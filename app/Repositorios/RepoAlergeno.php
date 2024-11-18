<?php


namespace App\Repositorios;


use App\Models\Alergeno;
use PDO;

class RepoAlergeno {

	public function findByName($nombre) {

		$con = Conexion::getConection();

		// Preparar la consulta para buscar por el email
		$stm = $con->prepare("SELECT * FROM alergeno WHERE nombre = :nombre");
		$stm->execute(['nombre' => $nombre]);

		// Obtener el resultado como un array asociativo
		$response = $stm->fetch(PDO::FETCH_ASSOC);

		if ($response) {
			// Crear un objeto Alérgeno con los datos obtenidos
			$alergeno = new Alergeno(
				$response['id'],
				$response['nombre'],
				$response['foto'],
			);

			return $alergeno;
		}

		// Si no se encuentra el alérgeno, devolver null
		return null;
	}


	public function getByIds($ids) {
		if (empty($ids)) {
			return [];
		}

		$con = Conexion::getConection();
		$placeholders = implode(',', array_fill(0, count($ids), '?'));
		$stm = $con->prepare("SELECT * FROM alergeno WHERE id IN ($placeholders)");
		$stm->execute($ids);

		$alergenos = [];
		while ($response = $stm->fetch(PDO::FETCH_ASSOC)) {
			$alergenos[$response['id']] = new Alergeno(
				$response['id'],
				$response['nombre'],
				$response['foto']
			);
		}

		return $alergenos; // Array asociativo: alérgeno_id => AlergenoObjeto
	}


	public function getAll() {

		$con = Conexion::getConection();

		// Preparar la consulta para buscar por el email
		$stm = $con->prepare("SELECT * FROM alergeno");

		$stm->execute();

		$objets = [];

		// Obtener el resultado como un array asociativo
		while ($response = $stm->fetch(PDO::FETCH_ASSOC)) {

			// Crear un objeto Alérgeno con los datos obtenidos
			$alergeno = new Alergeno(
				$response['id'],
				$response['nombre'],
				$response['foto'],
			);

			$objets[$response['id']] = $alergeno;
		}

		// Si no se encuentra el alérgeno, devolver null
		return $objets;
	}
}

?>