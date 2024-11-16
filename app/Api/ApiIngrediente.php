<?php

namespace App\Api;

use App\Models\Direccion;
use App\Models\Usuario;
use App\Repositorios\RepoAlergeno;
use App\Repositorios\RepoDireccion;
use App\Repositorios\RepoIngrediente;
use App\Repositorios\RepoUser;
use App\Utils\Validator;

class ApiIngrediente {

	public function handleRequest($data) {

		header('Content-Type: application/json');

		// Decidir qué método invocar según el valor de 'action'
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

	public function insertIngredients(){
		var_dump();
	}


}
