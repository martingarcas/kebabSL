<?php

namespace App\Api;

use App\Models\Ingrediente;
use App\Repositorios\RepoAlergeno;
use App\Repositorios\RepoIngrediente;

class ApiIngrediente {

	public function handleRequest($data) {
		header('Content-Type: application/json');

		if (isset($data['action'])) {
			switch ($data['action']) {
				case 'show':
					return $this->showIngredients($data);
				case 'insert':
					return $this->insertIngredients($data);
				default:
					http_response_code(400);
					return json_encode(['error' => 'Acción no válida.']);
			}
		} else {
			http_response_code(400);
			return json_encode(['error' => 'Acción no especificada.']);
		}
	}

	public function showIngredients() {

		$repoIngrediente 	= new RepoIngrediente();
		$repoAlergeno 		= new RepoAlergeno();
		$ingredientes 		= $repoIngrediente->getAll();
		$alergenos 			= $repoAlergeno->getAll();
		$ingredientesArray 	= [];
		$alergenosArray 	= [];

		//TODO: convertira json
		foreach ($ingredientes as $ingrediente) {

			$ingredientesArray[] = $ingrediente->getAsArray();
		}

		foreach ($alergenos as $alergeno) {

			$alergenosArray[] = $alergeno->getAsArray();
		}

		// Si todo fue exitoso, respondemos con los datos necesarios para la redirección
		http_response_code(200);
		return json_encode([
			'success' 		=> true,
			'ingredientes' 	=> $ingredientesArray,
			'alergenos' 	=> $alergenosArray
		]);
	}

	public function insertIngredients($data) {
		// Verificar que se está haciendo una petición POST y que existe el archivo
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
			$nombre = $data['nombre'];
			$precio = $data['precio'];
			$alergenos = isset($data['alergenos']) ? json_decode($data['alergenos'], true) : [];

			if (!is_array($alergenos)) {
				$alergenos = [];  // Asegura que siempre sea un array, incluso si no se recibe alérgenos
			}

			// Procesar la foto
			$foto = $_FILES['foto'];
			if ($foto['error'] !== UPLOAD_ERR_OK) {
				http_response_code(400);
				return json_encode(['error' => 'Error al subir la imagen.']);
			}

			// Guardar imagen
			$directorioDestino = $_SERVER['DOCUMENT_ROOT'] . '/img/ingredientes/';
			$nombreOriginal = basename($foto['name']);
			$rutaDestino = $directorioDestino . $nombreOriginal;

			if (file_exists($rutaDestino)) {
				$i = 1;
				$ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$nombreArchivo = pathinfo($nombreOriginal, PATHINFO_FILENAME) . '-' . $i . '.' . $ext;
				$rutaDestino = $directorioDestino . $nombreArchivo;
			} else {
				$nombreArchivo = $nombreOriginal;
			}

			if (!move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
				http_response_code(400);
				return json_encode(['error' => 'Error al guardar la imagen.']);
			}

			// Ruta relativa a la imagen para almacenar en la base de datos
			$rutaRelativa = '/img/ingredientes/' . $nombreArchivo;

			// Crear el objeto Ingrediente
			$ingrediente = new Ingrediente(
				null,
				$nombre,
				$rutaRelativa,
				$precio,
				[]
			);

			$repoIngrediente = new RepoIngrediente();
			$ingredienteCreado = $repoIngrediente->create($ingrediente);

			// Asociar los alérgenos con el ingrediente
			if (!empty($alergenos)) {
				$repoIngrediente->assoc_alergenos($ingredienteCreado->getId(), $alergenos);
			}

			// Asegurarte de que la respuesta sea siempre JSON
			http_response_code(200);
			echo json_encode([
				'success' => true,
				'message' => 'Ingrediente creado exitosamente.',
				'redirect_url' => '/ingredientes', // URL de redirección
				'ingrediente' => $ingredienteCreado->getAsArray() // Ingrediente recién creado
			]);
			exit; // Asegúrate de que no se imprima nada más
		} else {
			// Si la solicitud no es válida
			http_response_code(400);
			echo json_encode(['error' => 'No se ha enviado la imagen o los datos correctamente.']);
			exit;
		}
	}






}