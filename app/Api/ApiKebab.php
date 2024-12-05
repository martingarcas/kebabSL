<?php

namespace App\Api;

use App\Models\Kebab;
use App\Repositorios\RepoKebab;
use App\Repositorios\RepoIngrediente;
use App\Utils\Validator;

class ApiKebab {

	public function handleRequest($data) {
		header('Content-Type: application/json');

		if (isset($data['action'])) {
			switch ($data['action']) {
				case 'show':
					return $this->showKebabs($data);
				case 'insert':
					return $this->insertKebab($data);
				case 'update':
					return $this->updateKebab($data);
				case 'delete':
					return $this->deleteKebab($data);
				default:
					http_response_code(400);
					return json_encode(['error' => 'Acción no válida.']);
			}
		} else {
			http_response_code(400);
			return json_encode(['error' => 'Acción no especificada.']);
		}
	}

	public function showKebabs() {
		$repoKebab = new RepoKebab();
		$repoIngrediente = new RepoIngrediente();
		$kebabs = $repoKebab->getAll();
		$ingredientes = $repoIngrediente->getAll();
		$kebabsArray = [];
		$ingredientesArray = [];

		foreach ($kebabs as $kebab) {
			$kebabsArray[] = $kebab->getAsArray();
		}

		foreach ($ingredientes as $ingrediente) {
			$ingredientesArray[] = $ingrediente->getAsArray();
		}

		http_response_code(200);
		return json_encode([
			'success' => true,
			'kebabs' => $kebabsArray,
			'ingredientes' => $ingredientesArray
		]);
	}

	public function insertKebab($data) {

		$validator = new Validator(); // Instanciamos el validador

		// Definir las reglas de validación
		$camposRequeridos = [
			'nombre' => 'Requerido', // nombre es obligatorio
			'precio' => 'Requerido|Numerico' // precio es obligatorio y debe ser un número
		];

		// Validar los campos obligatorios y las validaciones personalizadas
		$errores = $validator->validarCampos($data, $camposRequeridos);

		$repoKebab = new RepoKebab();

		// Validar si el nombre ya está registrado (duplicado)
		if (empty($errores['nombre']) && $validator->validarDuplicado('nombre', $data['nombre'], $repoKebab)) {
			$errores['nombre'] = 'El nombre ya está registrado.';
		}

		// Si hay errores, devolvemos la respuesta con los errores encontrados
		if (count($errores) > 0) {
			http_response_code(400); // Código HTTP 400 para errores de validación
			return json_encode(['errores' => $errores]);
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$nombre = $data['nombre'];
			$precio = $data['precio'];
			$ingredientes = isset($data['ingredientes']) ? json_decode($data['ingredientes'], true) : [];

			// Validación del precio en el backend
			if ($precio < 2) {
				http_response_code(400);
				echo json_encode([
					'success' => false,
					'message' => 'El precio del kebab no puede ser menor a 2€.'
				]);
				exit;
			}

			if (!is_array($ingredientes)) {
				$ingredientes = [];  // Asegura que siempre sea un array, incluso si no se recibe ingredientes
			}

			// Inicializar la variable para la foto
			$foto = '';

			// Verificar si se ha subido una foto
			if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
				$foto = $_FILES['foto'];
				$directorioDestino = $_SERVER['DOCUMENT_ROOT'] . '/img/kebabs/';
				$nombreOriginal = basename($foto['name']);
				$rutaDestino = $directorioDestino . $nombreOriginal;

				if (file_exists($rutaDestino)) {
					$i = 1;
					$ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
					$nombreArchivo = pathinfo($nombreOriginal, PATHINFO_FILENAME);

					do {
						$nombreArchivo = pathinfo($nombreOriginal, PATHINFO_FILENAME) . '-' . $i . '.' . $ext;
						$rutaDestino = $directorioDestino . $nombreArchivo;
						$i++;
					} while (file_exists($rutaDestino));
				} else {
					$nombreArchivo = $nombreOriginal;
				}

				if (!move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
					http_response_code(400);
					return json_encode(['error' => 'Error al guardar la imagen.']);
				}

				$foto = '/img/kebabs/' . $nombreArchivo;
			}

			$kebab = new Kebab(
				null,
				$nombre,
				$foto,
				$precio,
				[]
			);
			$repoKebab = new RepoKebab();
			$kebabCreado = $repoKebab->create($kebab);

			if (!empty($ingredientes)) {
				$repoKebab->assoc_ingredientes($kebabCreado->getId(), $ingredientes);
			}

			http_response_code(200);
			echo json_encode([
				'success' => true,
				'message' => 'Kebab creado exitosamente.',
				'redirect_url' => '/kebabs',
				'kebab' => $kebabCreado->getAsArray()
			]);
			exit;
		} else {
			http_response_code(400);
			echo json_encode(['error' => 'No se ha enviado la imagen o los datos correctamente.']);
			exit;
		}
	}

	public function updateKebab($data) {

		$validator = new Validator(); // Instanciamos el validador
		$repoKebab = new RepoKebab();

		// Recuperar el ingrediente actual
		$kebabActual = $repoKebab->getById($data['id']);

		// Definir las reglas de validación
		$camposRequeridos = [
			'nombre' => 'Requerido', // nombre es obligatorio
			'precio' => 'Requerido|Numerico' // precio es obligatorio y debe ser un número
		];

		// Validar los campos obligatorios y las validaciones personalizadas
		$errores = $validator->validarCampos($data, $camposRequeridos);

		// Si el nombre del ingrediente no cambia, no validamos duplicados
		if ($kebabActual->getNombre() != $data['nombre']) {
			// Aquí se valida si el nombre es único solo si es diferente al actual
			if ($validator->validarDuplicado('nombre', $data['nombre'], $repoKebab)) {
				$errores['nombre'] = 'El nombre ya está registrado.';
			}
		}

		// Si hay errores, devolvemos la respuesta con los errores encontrados
		if (count($errores) > 0) {
			http_response_code(400); // Código HTTP 400 para errores de validación
			return json_encode(['errores' => $errores]);
		}

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405);
			return json_encode(['error' => 'Método no permitido.']);
		}

		if (!isset($data['id']) || empty($data['id'])) {
			http_response_code(400);
			return json_encode(['error' => 'ID del kebab no especificado.']);
		}

		if (!isset($data['nombre']) || !isset($data['precio'])) {
			http_response_code(400);
			return json_encode(['error' => 'Faltan datos obligatorios.']);
		}

		$kebab_id = $data['id'];
		$nombre = $data['nombre'];
		$precio = $data['precio'];
		$ingredientes = isset($data['ingredientes']) ? json_decode($data['ingredientes'], true) : [];

		// Validación del precio en el backend
		if ($precio < 2) {
			http_response_code(400);
			echo json_encode([
				'success' => false,
				'message' => 'El precio del kebab no puede ser menor a 2€.'
			]);
			exit;
		}

		if (!is_array($ingredientes)) {
			$ingredientes = [];
		}

		if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
			$foto = $_FILES['foto'];
			$directorioDestino = $_SERVER['DOCUMENT_ROOT'] . '/img/kebabs/';
			$nombreOriginal = basename($foto['name']);
			$rutaDestino = $directorioDestino . $nombreOriginal;

			if (file_exists($rutaDestino)) {
				$i = 1;
				$ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$nombreArchivo = pathinfo($nombreOriginal, PATHINFO_FILENAME);

				do {
					$nombreArchivo = pathinfo($nombreOriginal, PATHINFO_FILENAME) . '-' . $i . '.' . $ext;
					$rutaDestino = $directorioDestino . $nombreArchivo;
					$i++;
				} while (file_exists($rutaDestino));
			} else {
				$nombreArchivo = $nombreOriginal;
			}

			if (!move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
				http_response_code(400);
				return json_encode(['error' => 'Error al guardar la imagen.']);
			}

			$rutaRelativa = '/img/kebabs/' . $nombreArchivo;
		} else {
			$rutaRelativa = $data['foto'] ?? '';
		}

		$kebab = new Kebab($kebab_id, $nombre, $rutaRelativa, $precio, $ingredientes);

		try {
			$kebabActualizado = $repoKebab->update($kebab);

			http_response_code(200);
			return json_encode([
				'success' => true,
				'message' => 'Kebab actualizado exitosamente.',
				'redirect_url' => '/kebabs',
				'kebab' => $kebabActualizado->getAsArray()
			]);

		} catch (Exception $e) {
			http_response_code(500);
			return json_encode(['error' => 'Error al actualizar el kebab: ' . $e->getMessage()]);
		}
	}

	public function deleteKebab($data) {
		if (!isset($data['id']) || empty($data['id'])) {
			http_response_code(400);
			return json_encode(['error' => 'ID del kebab no especificado.']);
		}

		$kebab_id = $data['id'];
		$repoKebab = new RepoKebab();
		$result = $repoKebab->delete($kebab_id);

		if ($result) {
			http_response_code(200);
			return json_encode([
				'success' => true,
				'message' => 'Kebab eliminado exitosamente.',
				'redirect_url' => '/kebabs',
			]);
		} else {
			http_response_code(500);
			return json_encode(['error' => 'Error al eliminar el kebab.']);
		}
	}

}
