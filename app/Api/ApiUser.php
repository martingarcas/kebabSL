<?php

namespace App\Api;

use App\Repositorios\Conexion;
use App\Repositorios\RepoUser;

class ApiUser {

	public function createUser() {

		$repoUser = new RepoUser(Conexion::getConection());


	}

}