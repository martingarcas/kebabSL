<?php


namespace App\Repositorios;


use App\Models\Alergeno;

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
}

?>