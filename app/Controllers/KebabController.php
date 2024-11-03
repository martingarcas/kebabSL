<?php


namespace App\Controllers;


use App\Repositorios\Conexion;
use App\Repositorios\RepoKebab;

class KebabController {

	public function editKebab ($data) {

		$id 	= $data['id'];
		$nombre = $data['nombre'];
		$foto 	= $data['foto'];
		$precio = $data['precio'];

		$repoKebab = new RepoKebab(Conexion::getConection());

		$kebab = [
			"id" 		=> $id,
			"nombre" 	=> $nombre,
			"foto" 		=> $foto,
			"precio" 	=> $precio,
		];

		$response = $repoKebab->update($kebab);

		//...

		return $response;
	}



}