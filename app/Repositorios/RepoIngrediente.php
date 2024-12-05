<?php

namespace App\Repositorios;

use App\Models\Ingrediente;
use PDO;

class RepoIngrediente {

	public function create(Ingrediente $ingrediente) {
		$con = Conexion::getConection();

		// Preparar y ejecutar la inserción del ingrediente
		$stm = $con->prepare("INSERT INTO ingrediente (nombre, foto, precio) VALUES (:nombre, :foto, :precio)");
		$stm->execute([
			'nombre' 	=> $ingrediente->getNombre(),
			'foto' 		=> $ingrediente->getFoto(),
			'precio' 	=> $ingrediente->getPrecio()
		]);

		// Obtener el ID del ingrediente recién insertado
		$ingrediente_id = $con->lastInsertId();

		// Asignar el ID al objeto Ingrediente usando el setter
		$ingrediente->setId($ingrediente_id);

		return $ingrediente;
	}

	public function update(Ingrediente $ingrediente) {
		$con = Conexion::getConection();

		// Iniciar una transacción
		$con->beginTransaction();

		try {
			// 1. Actualizar el ingrediente en la tabla `ingrediente`
			$stm = $con->prepare("UPDATE ingrediente SET nombre = :nombre, precio = :precio, foto = :foto WHERE id = :id");
			$stm->execute([
				'id' 		=> $ingrediente->getId(),
				'nombre' 	=> $ingrediente->getNombre(),
				'precio' 	=> $ingrediente->getPrecio(),
				'foto' 		=> $ingrediente->getFoto()
			]);

			// 2. Eliminar las relaciones existentes con los alérgenos
			$deleteStmt = $con->prepare("DELETE FROM ingrediente_has_alergeno WHERE ingrediente_id = :id");
			$deleteStmt->execute([
				'id' => $ingrediente->getId()
			]);

			// 3. Asignar las nuevas relaciones con los alérgenos usando el método assoc_alergenos
			if (!empty($ingrediente->getAlergenos())) {
				// Aquí llamamos al método para asociar los alérgenos
				$this->assoc_alergenos($ingrediente->getId(), $ingrediente->getAlergenos());
			}

			// 4. Si todo fue exitoso, hacer commit
			$con->commit();

			return $ingrediente; // Actualización exitosa

		} catch (Exception $e) {
			// Si hay algún error, hacer rollback
			$con->rollBack();
			throw $e; // Lanzamos la excepción para que el controlador o el método superior lo maneje
		}
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
				error_log("El alérgeno con ID $alergeno_id no existe.");
			}
		}
	}

	public function getByIds($ids) {
		if (empty($ids)) {
			return [];
		}

		$con = Conexion::getConection();
		$placeholders = implode(',', array_fill(0, count($ids), '?'));
		$stm = $con->prepare("SELECT * FROM ingrediente WHERE id IN ($placeholders)");
		$stm->execute($ids);

		$ingredientes = [];
		while ($response = $stm->fetch(PDO::FETCH_ASSOC)) {
			$ingrediente = new Ingrediente(
				$response['id'],
				$response['nombre'],
				$response['foto'],
				$response['precio'],
				[] // Inicialmente dejamos los alérgenos como un array vacío
			);

			$ingredientes[$response['id']] = $ingrediente;
		}

		// Obtener los alérgenos asociados para los ingredientes
		$ingrediente_ids = array_keys($ingredientes);
		$ingrediente_alergenos = $this->__mm__alergenos($ingrediente_ids);

		// Asignar los alérgenos a cada ingrediente
		foreach ($ingredientes as $ingrediente_id => $ingrediente) {
			$ingrediente->setAlergenos($ingrediente_alergenos[$ingrediente_id] ?? []);
		}

		return $ingredientes;
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

//		$objets(ingredientes) = [
//			1 => [10, 11],  // Ingrediente_id = 1 tiene alérgenos con IDs 10 y 11
//			2 => [10, 12],  // Ingrediente_id = 2 tiene alérgenos con IDs 10 y 12
//		];

//		$alergenos = [
//			10 => (objeto Alergeno con id 10),  // Alergeno con ID 10
//			11 => (objeto Alergeno con id 11),  // Alergeno con ID 11
//			12 => (objeto Alergeno con id 12),  // Alergeno con ID 12
//		];

		return $_mm_resolve; // Retorna la relación ingrediente_id => [alérgeno_objetos]
	}

	// Método para verificar si existe un ingrediente por un campo genérico
	public function existePorCampo($campo, $valor) {

		$con = Conexion::getConection();
		$stm = $con->prepare("SELECT COUNT(*) FROM ingrediente WHERE $campo = :valor");
		$stm->execute(['valor' => $valor]);

		return $stm->fetchColumn() > 0;
	}

	public function getById($id) {
		$con = Conexion::getConection();

		// Preparar y ejecutar la consulta para obtener un ingrediente por su ID
		$stm = $con->prepare("SELECT * FROM ingrediente WHERE id = :id");
		$stm->execute(['id' => $id]);

		// Recuperar el resultado
		$response = $stm->fetch(PDO::FETCH_ASSOC);

		// Si no existe el ingrediente, retornar null
		if (!$response) {
			return null;
		}

		// Crear el objeto Ingrediente con los datos obtenidos
		$ingrediente = new Ingrediente(
			$response['id'],
			$response['nombre'],
			$response['foto'],
			$response['precio'],
			[] // Inicialmente no asignamos los alérgenos
		);

		// Obtener y asignar los alérgenos asociados
		$ingrediente_alergenos = $this->__mm__alergenos([$response['id']]);

		// Asignar los alérgenos al objeto Ingrediente
		$ingrediente->setAlergenos($ingrediente_alergenos[$response['id']] ?? []);

		return $ingrediente;
	}

}

?>
