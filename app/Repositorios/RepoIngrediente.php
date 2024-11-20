<?php

namespace App\Repositorios;

use App\Models\Alergeno;
use App\Models\Ingrediente;
use PDO;

class RepoIngrediente {

	public function create(Ingrediente $ingrediente) {
		$con = Conexion::getConection();

		// Preparar y ejecutar la inserción del ingrediente
		$stm = $con->prepare("INSERT INTO ingrediente (nombre, foto, precio) VALUES (:nombre, :foto, :precio)");
		$stm->execute([
			'nombre' => $ingrediente->getNombre(),
			'foto' => $ingrediente->getFoto(),
			'precio' => $ingrediente->getPrecio()
		]);

		// Obtener el ID del ingrediente recién insertado
		$ingrediente_id = $con->lastInsertId();

		// Asignar el ID al objeto Ingrediente usando el setter
		$ingrediente->setId($ingrediente_id);

		return $ingrediente;
	}

	public function delete($ingrediente_id) {
		$con = Conexion::getConection();

		try {
			// Iniciar transacción
			$con->beginTransaction();

			// Eliminar asociaciones con alérgenos
			$stm = $con->prepare("DELETE FROM ingrediente_has_alergeno WHERE Ingrediente_id = :ingrediente_id");
			$stm->execute(['ingrediente_id' => $ingrediente_id]);

			// Eliminar el ingrediente
			$stm = $con->prepare("DELETE FROM ingrediente WHERE id = :id");
			$stm->execute(['id' => $ingrediente_id]);

			// Commit de la transacción
			$con->commit();
			return true; // Todo salió bien
		} catch (\Exception $e) {
			// En caso de error, hacer rollback
			$con->rollBack();
			return false; // Indicar que hubo un error
		}
	}

	public function assoc_alergenos($ingrediente_id, $alergenos) {
		$con = Conexion::getConection();

		if (empty($alergenos)) {
			return; // Si no hay alérgenos, no hacemos nada
		}

		foreach ($alergenos as $alergeno_id) {
			// Verificar que el alérgeno existe (opcional, depende de tus necesidades)
			$stm = $con->prepare("SELECT id FROM alergeno WHERE id = :alergeno_id");
			$stm->execute(['alergeno_id' => $alergeno_id]);
			$alergeno = $stm->fetch(PDO::FETCH_ASSOC);

			if ($alergeno) {
				// Insertar en la tabla intermedia 'ingrediente_has_alergeno'
				$stm = $con->prepare(
					"INSERT INTO ingrediente_has_alergeno (Ingrediente_id, Alergeno_id) 
                 VALUES (:ingrediente_id, :alergeno_id)"
				);
				$stm->execute([
					'ingrediente_id' => $ingrediente_id,
					'alergeno_id' => $alergeno_id
				]);
			} else {
				// Manejar el caso en que el alérgeno no existe (opcional)
				error_log("El alérgeno con ID $alergeno_id no existe.");
			}
		}
	}

	public function getByIds($ids) {
		$con = Conexion::getConection();

		// Verificar si $ids es un array y contiene valores
		if (!is_array($ids) || empty($ids)) {
			return [];
		}

		// Convertir los IDs en enteros
		$ids = array_map('intval', $ids);

		// Preparar la consulta para buscar por los IDs de los ingredientes
		$stm = $con->prepare("SELECT * FROM ingrediente WHERE id IN (" . implode(',', array_fill(0, count($ids), '?')) . ")");
		$stm->execute($ids);

		$objets = [];

		// Obtener el resultado como un array asociativo
		while ($response = $stm->fetch(PDO::FETCH_ASSOC)) {
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

		// Obtener los alérgenos asociados a los ingredientes
		$ingrediente_alergenos = $this->__mm__alergenos($ingredientes_keys);

		// Asignar los alérgenos a cada ingrediente
		foreach ($objets as $ingrediente_k => $ingrediente) {
			$ingrediente->setAlergenos($ingrediente_alergenos[$ingrediente_k] ?? []);
		}

		return $objets;
	}

	public function getAll() {
		$con = Conexion::getConection();

		$stm = $con->prepare("SELECT * FROM ingrediente");
		$stm->execute();

		$objets = [];
		while ($response = $stm->fetch(PDO::FETCH_ASSOC)) {
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

		// Obtener los alérgenos asociados a los ingredientes
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

		// Crear los placeholders para la consulta
		$placeholders = implode(',', array_fill(0, count($ids), '?'));
		$stm = $con->prepare(
			"SELECT Ingrediente_id, Alergeno_id FROM ingrediente_has_alergeno WHERE Ingrediente_id IN ($placeholders)"
		);

		// Ejecutar la consulta con los IDs de los ingredientes
		$stm->execute($ids);

		$objets = [];  // Relacionará ingrediente_id con alérgeno_ids
		$alergenos_tmp = [];

		while ($response = $stm->fetch(PDO::FETCH_ASSOC)) {
			$ingrediente_id = $response['Ingrediente_id'];
			$alergeno_id = $response['Alergeno_id'];

			// Asociar los alérgenos con el ingrediente
			$objets[$ingrediente_id][] = $alergeno_id;

			// Guardar los IDs de los alérgenos únicos
			$alergenos_tmp[$alergeno_id] = $alergeno_id;
		}

		// Si no hay alérgenos, retornamos un array vacío
		if (empty($alergenos_tmp)) {
			return [];
		}

		// Obtener los objetos completos de los alérgenos por sus IDs
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

		return $_mm_resolve; // Retorna la relación ingrediente_id => [alérgeno_objetos]
	}
}

?>
