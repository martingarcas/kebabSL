<?php


namespace App\Repositorios;


use App\Models\Alergeno;
use App\Models\Ingrediente;
use PDO;

class RepoIngrediente {

	public function create(Ingrediente $ingrediente) {

		$con = Conexion::getConection();

		$nombre 	= $ingrediente->getNombre();
		$foto 		= $ingrediente->getFoto();
		$precio 	= $ingrediente->getPrecio();
		$alergenos 	= $ingrediente->getAlergenos();


		$stm = $con->prepare(
			"INSERT INTO ingrediente (nombre, foto, precio) VALUES (:nombre, :foto, :precio)"
		);

		$stm->execute([
			'nombre' 		=> $nombre,
			'foto' 			=> $foto,
			'precio' 		=> $precio,
		]);

		$ingrediente_id = $con->lastInsertId();

		$ingrediente->setId($ingrediente_id);

		$this->assoc_alergenos($ingrediente_id, $alergenos);

		return $ingrediente;
	}

	public function assoc_alergenos ($ingrediente_id, $alergenos) {

		$con = Conexion::getConection();

		foreach ($alergenos as $alergeno) {


			$stm = $con->prepare(
				"INSERT INTO ingrediente_has_alergeno (Ingrediente_id, Alergeno_id) VALUES (:ingrediente_id, :alergeno_id)"
			);

			$stm->execute([
				'ingrediente_id' 		=> $ingrediente_id,
				'alergeno_id' 			=> ($alergeno instanceof Alergeno) ? $alergeno->getId() : $alergeno,
			]);
		}
	}

	public function getByIds($ids) {

		$con = Conexion::getConection();

		// Preparar la consulta para buscar por el email
		$stm = $con->prepare("SELECT * FROM ingrediente WHERE id in (:id)");
		$stm->execute(['id' => implode(',', $ids)]);

		$objets = [];

		// Obtener el resultado como un array asociativo
		while ($response = $stm->fetch(PDO::FETCH_ASSOC)) {

			// Crear un objeto Alérgeno con los datos obtenidos
			$ingrediente = new Ingrediente(
				$response['id'],
				$response['nombre'],
				$response['foto'],
				$response['precio'],
				$response['alergenos']
			);

			$objets[$response['id']] = $ingrediente;
		}

		$ingredientes_keys = array_keys($objets);

		$ingrediente_alergenos = $this->__mm__alergenos($ingredientes_keys);

		/**
		 * @var Ingrediente $ingrediente
		 */
		foreach ($objets as $ingrediente_k => $ingrediente) {

			$ingrediente->setAlergenos($ingrediente_alergenos[$ingrediente_k] ?? []);
		}

		// Si no se encuentra el alérgeno, devolver null
		return $objets;
	}

	public function getAll() {

		$con = Conexion::getConection();

		// Preparar la consulta para buscar por el email
		$stm = $con->prepare("SELECT * FROM ingrediente");

		$stm->execute();

		$objets = [];

		// Obtener el resultado como un array asociativo
		while ($response = $stm->fetch(PDO::FETCH_ASSOC)) {

			// Crear un objeto Alérgeno con los datos obtenidos
			$ingrediente = new Ingrediente(
				$response['id'],
				$response['nombre'],
				$response['foto'],
				$response['precio'],
				$response['alergenos'] ?? [],
			);

			$objets[$response['id']] = $ingrediente;
		}

		$ingredientes_keys = array_keys($objets);

		$ingrediente_alergenos = $this->__mm__alergenos($ingredientes_keys);

		/**
		 * @var Ingrediente $ingrediente
		 */
		foreach ($objets as $ingrediente_k => $ingrediente) {

			$ingrediente->setAlergenos($ingrediente_alergenos[$ingrediente_k] ?? null);
		}

		// Si no se encuentra el alérgeno, devolver null
		return $objets;
	}

	protected function __mm__alergenos ($ids) {

		if(!$ids) {
			return [];
		}

		$con = Conexion::getConection();

		// Preparar la consulta para buscar por el email
		$stm = $con->prepare("SELECT * FROM ingrediente_has_alergeno where Ingrediente_id IN (:ids)");

		$stm->execute(['ids' => implode(',', $ids)]);

		$objets = [];

		$alergenos_tmp = [];

		// Obtener el resultado como un array asociativo
		while ($response = $stm->fetch(PDO::FETCH_ASSOC)) {

			$objets[$response['Ingrediente_id']][] = $response['Alergeno_id'];

			$alergenos_tmp[$response['Alergeno_id']] = $response['Alergeno_id'];
		}

		$RepoAlergenos = new RepoAlergeno();

		$alergenos = $RepoAlergenos->getByIds($alergenos_tmp);

		$_mm_resolve = [];

		foreach ($objets as $ingrediente_id => $ingrediente) {

			foreach ($ingrediente as $alergeno_id) {

				if(!isset($_mm_resolve[$ingrediente_id])){
					$_mm_resolve[$ingrediente_id] = [];
				}

				$_mm_resolve[$ingrediente_id][] = $alergenos[$alergeno_id];
			}
		}

		// Si no se encuentra el alérgeno, devolver null
		return $_mm_resolve;
	}
}

?>