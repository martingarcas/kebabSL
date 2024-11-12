<?php

namespace App\Api;

use App\Models\Direccion;
use App\Models\Usuario;
use App\Repositorios\Conexion;
use App\Repositorios\RepoDireccion;
use App\Repositorios\RepoUser;
use PDOException;

class ApiUser {

	// Método para crear el usuario y la dirección
	public function crearUsuario($data) {

		$pdo = Conexion::getConection();

		// Crear los repositorios para usuario y dirección
		$repoUser 		= new RepoUser($pdo);
		$repoDireccion 	= new RepoDireccion($pdo);

		try {

			// Iniciar la transacción
			$pdo->beginTransaction();

			// Crear el objeto Usuario, pasando solo los datos obligatorios
			$usuario = new Usuario(
				$data['nombre'],         // nombre
				$data['apellido1'],      // apellido1 (opcional)
				$data['apellido2'],      // apellido2 (opcional)
				$data['contrasenna'],    // contrasenna (obligatorio)
				$data['telefono'],       // telefono (opcional)
				$data['email'],          // email (obligatorio)
				$data['dni'],            // dni (obligatorio)
				$data['foto'],           // foto (opcional)
				$data['monedero'],       // monedero (opcional)
				$data['carrito']         // carrito (opcional)
			);

			// Insertar el usuario en la base de datos
			$repoUser->create($usuario);

			$usuarioId = $usuario->getId();

			// Si no se obtuvo un ID de usuario, lanzar una excepción
			if (!$usuarioId) {
				throw new PDOException("No se pudo obtener el ID del usuario.");
			}

			// Crear el objeto Dirección y asociarlo al usuario
			$direccion = new Direccion($data['calle'], $data['numero'], $data['activa']);

			// Insertar la dirección en la base de datos
			$repoDireccion->create($direccion, $usuarioId);

			// Confirmar la transacción
			$pdo->commit();

			// Si todo sale bien, devolver una respuesta de éxito
			//devolver un json/encode
			return ['status' => 'success', 'message' => 'Usuario y dirección creados con éxito'];

		} catch (\Exception $e) {
			// Si ocurre un error, devolver un mensaje de error
			$pdo->rollBack();
			return ['status' => 'error', 'message' => $e->getMessage()];
		}
	}

}