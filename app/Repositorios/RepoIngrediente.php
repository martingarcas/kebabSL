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

	public function assoc_alergenos($ingrediente_id, $alergenos) {
		$con = Conexion::getConection();

		// Verificar que $alergenos sea un array y no esté vacío
		if (!is_array($alergenos) || empty($alergenos)) {
			echo "No hay alérgenos para asociar al ingrediente.";
			return;
		}

		foreach ($alergenos as $alergeno) {
			// Asegúrate de que cada $alergeno sea una instancia de Alergeno
			if ($alergeno instanceof Alergeno) {
				$stm = $con->prepare(
					"INSERT INTO ingrediente_has_alergeno (Ingrediente_id, Alergeno_id) VALUES (:ingrediente_id, :alergeno_id)"
				);

				$stm->execute([
					'ingrediente_id' => $ingrediente_id,
					'alergeno_id' => $alergeno->getId(),
				]);
			} else {
				echo "El alérgeno no es válido.";
			}
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
				[] // Inicializa los alérgenos como un array vacío
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

		$stm = $con->prepare("SELECT * FROM ingrediente");
		$stm->execute();

		$objets = [];
		while ($response = $stm->fetch(PDO::FETCH_ASSOC)) {
			// Crear el objeto Ingrediente
			$ingrediente = new Ingrediente(
				$response['id'],
				$response['nombre'],
				$response['foto'],
				$response['precio'],
				[] // Alérgenos se agregarán después
			);

			$objets[$response['id']] = $ingrediente;
		}

		$ingredientes_keys = array_keys($objets);

		// Obtener los alérgenos asociados
		$ingrediente_alergenos = $this->__mm__alergenos($ingredientes_keys);

		// Asignar los alérgenos a cada ingrediente
		foreach ($objets as $ingrediente_k => $ingrediente) {
			$ingrediente->setAlergenos($ingrediente_alergenos[$ingrediente_k] ?? []);
		}

		return $objets;
	}


	protected function __mm__alergenos($ids) {
		if (empty($ids)) {
			return [];
		}

		$con = Conexion::getConection();

		// Crear los placeholders
		$placeholders = implode(',', array_fill(0, count($ids), '?'));
		$stm = $con->prepare("SELECT Ingrediente_id, Alergeno_id FROM ingrediente_has_alergeno WHERE Ingrediente_id IN ($placeholders)");

		// Ejecutar la consulta con los IDs de los ingredientes
		$stm->execute($ids);

		$objets = []; // Relacionará ingrediente_id con alérgeno_ids
		$alergenos_tmp = [];

		while ($response = $stm->fetch(PDO::FETCH_ASSOC)) {
			$ingrediente_id = $response['Ingrediente_id'];
			$alergeno_id = $response['Alergeno_id'];

			// Asociar los alérgenos con el ingrediente
			$objets[$ingrediente_id][] = $alergeno_id;

			// Guardar los IDs de los alérgenos únicos
			$alergenos_tmp[$alergeno_id] = $alergeno_id;
		}

		// Obtener los objetos completos de los alérgenos por sus IDs
		if (empty($alergenos_tmp)) {
			return [];
		}

		$RepoAlergenos = new RepoAlergeno();
		$alergenos = $RepoAlergenos->getByIds(array_values($alergenos_tmp));

		// Asociar los objetos de alérgenos a los ingredientes
		$_mm_resolve = [];
		foreach ($objets as $ingrediente_id => $alergeno_ids) {
			foreach ($alergeno_ids as $alergeno_id) {
				if (isset($alergenos[$alergeno_id])) {
					$_mm_resolve[$ingrediente_id][] = $alergenos[$alergeno_id];
				}
			}
		}

		return $_mm_resolve; // Retorna un array con la relación ingrediente_id => [alérgeno_objetos]
	}


}

?>