<?php

namespace App\Repositorios;

use App\Models\Kebab;
use PDO;

class RepoKebab {

	public function create(Kebab $kebab) {
		$con = Conexion::getConection();

		// Preparar y ejecutar la inserción del kebab
		$stm = $con->prepare("INSERT INTO kebab (nombre, foto, precio) VALUES (:nombre, :foto, :precio)");
		$stm->execute([
			'nombre' 	=> $kebab->getNombre(),
			'foto' 		=> $kebab->getFoto(),
			'precio' 	=> $kebab->getPrecio()
		]);

		// Obtener el ID del kebab recién insertado
		$kebab_id = $con->lastInsertId();

		// Asignar el ID al objeto Kebab usando el setter
		$kebab->setId($kebab_id);

		return $kebab;
	}

	public function update(Kebab $kebab) {
		$con = Conexion::getConection();

		// Iniciar una transacción
		$con->beginTransaction();

		try {
			// 1. Actualizar el kebab en la tabla `kebab`
			$stm = $con->prepare("UPDATE kebab SET nombre = :nombre, precio = :precio, foto = :foto WHERE id = :id");
			$stm->execute([
				'id' 		=> $kebab->getId(),
				'nombre' 	=> $kebab->getNombre(),
				'precio' 	=> $kebab->getPrecio(),
				'foto' 		=> $kebab->getFoto()
			]);

			// 2. Eliminar las relaciones existentes con los ingredientes
			$deleteStmt = $con->prepare("DELETE FROM kebab_has_ingrediente WHERE kebab_id = :id");
			$deleteStmt->execute([
				'id' => $kebab->getId()
			]);

			// 3. Asignar las nuevas relaciones con los ingredientes usando el método assoc_ingredientes
			if (!empty($kebab->getIngredientes())) {
				$this->assoc_ingredientes($kebab->getId(), $kebab->getIngredientes());
			}

			// 4. Si todo fue exitoso, hacer commit
			$con->commit();

			return $kebab; // Actualización exitosa

		} catch (Exception $e) {
			// Si hay algún error, hacer rollback
			$con->rollBack();
			throw $e; // Lanzamos la excepción para que el controlador o el método superior lo maneje
		}
	}

	public function delete($kebab_id) {
		$con = Conexion::getConection();

		try {
			// Iniciar transacción
			$con->beginTransaction();

			// Eliminar asociaciones con ingredientes
			$stm = $con->prepare("DELETE FROM kebab_has_ingrediente WHERE Kebab_id = :kebab_id");
			$stm->execute(['kebab_id' => $kebab_id]);

			// Eliminar el kebab
			$stm = $con->prepare("DELETE FROM kebab WHERE id = :id");
			$stm->execute(['id' => $kebab_id]);

			// Commit de la transacción
			$con->commit();
			return true; // Todo salió bien
		} catch (\Exception $e) {
			// En caso de error, hacer rollback
			$con->rollBack();
			return false; // Indicar que hubo un error
		}
	}

	public function assoc_ingredientes($kebab_id, $ingredientes) {
		$con = Conexion::getConection();

		if (empty($ingredientes)) {
			return; // Si no hay ingredientes, no hacemos nada
		}

		foreach ($ingredientes as $ingrediente_id) {
			$stm = $con->prepare("SELECT id FROM ingrediente WHERE id = :ingrediente_id");
			$stm->execute(['ingrediente_id' => $ingrediente_id]);
			$ingrediente = $stm->fetch(PDO::FETCH_ASSOC);

			if ($ingrediente) {
				// Insertar en la tabla intermedia 'kebab_has_ingrediente'
				$stm = $con->prepare(
					"INSERT INTO kebab_has_ingrediente (kebab_id, ingrediente_id) 
                     VALUES (:kebab_id, :ingrediente_id)"
				);
				$stm->execute([
					'kebab_id' => $kebab_id,
					'ingrediente_id' => $ingrediente_id
				]);
			} else {
				error_log("El ingrediente con ID $ingrediente_id no existe.");
			}
		}
	}

	public function getByIds($ids) {
		if (empty($ids)) {
			return [];
		}

		$con = Conexion::getConection();
		$placeholders = implode(',', array_fill(0, count($ids), '?'));
		$stm = $con->prepare("SELECT * FROM kebab WHERE id IN ($placeholders)");
		$stm->execute($ids);

		$kebabs = [];
		while ($response = $stm->fetch(PDO::FETCH_ASSOC)) {
			$kebab = new Kebab(
				$response['id'],
				$response['nombre'],
				$response['foto'],
				$response['precio'],
				[] // Inicialmente dejamos los ingredientes como un array vacío
			);

			$kebabs[$response['id']] = $kebab;
		}

		// Obtener los ingredientes asociados para los kebabs
		$kebab_ids = array_keys($kebabs);
		$kebab_ingredientes = $this->__mm__ingredientes($kebab_ids);

		// Asignar los ingredientes a cada kebab
		foreach ($kebabs as $kebab_id => $kebab) {
			$kebab->setIngredientes($kebab_ingredientes[$kebab_id] ?? []);
		}

		return $kebabs;
	}

	public function getAll() {
		$con = Conexion::getConection();

		$stm = $con->prepare("SELECT * FROM kebab");
		$stm->execute();

		$objets = [];
		while ($response = $stm->fetch(PDO::FETCH_ASSOC)) {
			$kebab = new Kebab(
				$response['id'],
				$response['nombre'],
				$response['foto'],
				$response['precio'],
				[] // Ingredientes se agregarán después
			);

			$objets[$response['id']] = $kebab;
		}

		$kebab_keys = array_keys($objets);

		// Obtener los ingredientes asociados a los kebabs
		$kebab_ingredientes = $this->__mm__ingredientes($kebab_keys);

		// Asignar los ingredientes a cada kebab
		foreach ($objets as $kebab_k => $kebab) {
			$kebab->setIngredientes($kebab_ingredientes[$kebab_k] ?? []);
		}

		return $objets;
	}

	protected function __mm__ingredientes($ids) {
		if (empty($ids)) {
			return [];
		}

		$con = Conexion::getConection();

		// Crear los placeholders para la consulta
		$placeholders = implode(',', array_fill(0, count($ids), '?'));
		$stm = $con->prepare(
			"SELECT Kebab_id, Ingrediente_id FROM kebab_has_ingrediente WHERE Kebab_id IN ($placeholders)"
		);

		// Ejecutar la consulta con los IDs de los kebabs
		$stm->execute($ids);

		$objets = [];  // Relacionará kebab_id con ingrediente_ids
		$ingredientes_tmp = [];

		while ($response = $stm->fetch(PDO::FETCH_ASSOC)) {
			$kebab_id = $response['Kebab_id'];
			$ingrediente_id = $response['Ingrediente_id'];

			// Asociar los ingredientes con el kebab
			$objets[$kebab_id][] = $ingrediente_id;

			// Guardar los IDs de los ingredientes únicos
			$ingredientes_tmp[$ingrediente_id] = $ingrediente_id;
		}

		// Si no hay ingredientes, retornamos un array vacío
		if (empty($ingredientes_tmp)) {
			return [];
		}

		// Obtener los objetos completos de los ingredientes por sus IDs
		$RepoIngredientes = new RepoIngrediente();
		$ingredientes = $RepoIngredientes->getByIds(array_values($ingredientes_tmp));

		// Asociar los objetos de ingredientes a los kebabs
		$_mm_resolve = [];
		foreach ($objets as $kebab_id => $ingrediente_ids) {
			foreach ($ingrediente_ids as $ingrediente_id) {
				if (isset($ingredientes[$ingrediente_id])) {
					$_mm_resolve[$kebab_id][] = $ingredientes[$ingrediente_id];
				}
			}
		}

		//$objets(kebabs) = [
//			1 => [10, 11],  // Kebab_id = 1 tiene ingredientes con IDs 10 y 11
//			2 => [10, 12],  // Kebab_id = 2 tiene ingredientes con IDs 10 y 12
//		];

//		$ingredientes = [
//			10 => (objeto Ingrediente con id 10),  // Ingrediente con ID 10
//			11 => (objeto Ingrediente con id 11),  // Ingrediente con ID 11
//			12 => (objeto Ingrediente con id 12),  // Ingrediente con ID 12
//		];

		return $_mm_resolve; // Retorna la relación kebab_id => [ingrediente_objetos]
	}

	// Método para verificar si existe un kebab por un campo genérico
	public function existePorCampo($campo, $valor) {

		$con = Conexion::getConection();
		$stm = $con->prepare("SELECT COUNT(*) FROM kebab WHERE $campo = :valor");
		$stm->execute(['valor' => $valor]);

		return $stm->fetchColumn() > 0;
	}

}

?>
