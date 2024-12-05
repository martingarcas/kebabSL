<?php

namespace App\Api;

use App\Models\Ingrediente;
use App\Repositorios\RepoAlergeno;
use App\Repositorios\RepoIngrediente;
use App\Utils\Validator;
use Dompdf\Dompdf;

class ApiIngrediente {

	public function handleRequest($data) {

		header('Content-Type: application/json');

		if (isset($data['action'])) {
			switch ($data['action']) {
				case 'show':
					return $this->showIngredients($data);
				case 'insert':
					return $this->insertIngredients($data);
				case 'update':
					return $this->updateIngredients($data);
				case 'delete':
					return $this->deleteIngredients($data);
				case 'pdf':
					return $this->generatePdf($data);
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
		$validator = new Validator(); // Instanciamos el validador

		// Definir las reglas de validación
		$camposRequeridos = [
			'nombre' => 'Requerido', // nombre es obligatorio
			'precio' => 'Requerido|Numerico' // precio es obligatorio y debe ser un número
		];

		// Validar los campos obligatorios y las validaciones personalizadas
		$errores = $validator->validarCampos($data, $camposRequeridos);

		$repoIngrediente = new RepoIngrediente();

		// Validar si el nombre ya está registrado (duplicado)
		if (empty($errores['nombre']) && $validator->validarDuplicado('nombre', $data['nombre'], $repoIngrediente)) {
			$errores['nombre'] = 'El nombre ya está registrado.';
		}

		// Si hay errores, devolvemos la respuesta con los errores encontrados
		if (count($errores) > 0) {
			http_response_code(400); // Código HTTP 400 para errores de validación
			return json_encode(['errores' => $errores]);
		}

		// Verificar que se está haciendo una petición POST
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$nombre = $data['nombre'];
			$precio = $data['precio'];
			$alergenos = isset($data['alergenos']) ? json_decode($data['alergenos'], true) : [];

			if (!is_array($alergenos)) {
				$alergenos = [];  // Asegura que siempre sea un array, incluso si no se recibe alérgenos
			}

			// Inicializar la variable para la foto
			$foto = '';

			// Verificar si se ha subido una foto
			if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
				// Procesar la foto solo si se ha subido correctamente
				$foto = $_FILES['foto'];
				$directorioDestino = $_SERVER['DOCUMENT_ROOT'] . '/img/ingredientes/';
				$nombreOriginal = basename($foto['name']);
				$rutaDestino = $directorioDestino . $nombreOriginal;

				// Comprobar si ya existe el archivo
				if (file_exists($rutaDestino)) {
					$i = 1;
					$ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
					$nombreArchivo = pathinfo($nombreOriginal, PATHINFO_FILENAME); // Obtener el nombre del archivo sin la extensión

					// Generar nuevo nombre con sufijo numérico hasta que el archivo no exista
					do {
						$nombreArchivo = pathinfo($nombreOriginal, PATHINFO_FILENAME) . '-' . $i . '.' . $ext;
						$rutaDestino = $directorioDestino . $nombreArchivo;
						$i++; // Incrementar el contador
					} while (file_exists($rutaDestino)); // Comprobar si el nuevo nombre ya existe
				} else {
					$nombreArchivo = $nombreOriginal;
				}

				// Mover el archivo al directorio de destino
				if (!move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
					http_response_code(400);
					return json_encode(['error' => 'Error al guardar la imagen.']);
				}

				// Ruta relativa a la imagen para almacenar en la base de datos
				$foto = '/img/ingredientes/' . $nombreArchivo;
			}

			// Crear el objeto Ingrediente
			$ingrediente = new Ingrediente(
				null,
				$nombre,
				$foto,  // Aquí usamos la variable $foto que puede estar vacía o tener la ruta de la imagen
				$precio,
				[]
			);

			$repoIngrediente = new RepoIngrediente();
			$ingredienteCreado = $repoIngrediente->create($ingrediente);

			// Asociar los alérgenos con el ingrediente
			if (!empty($alergenos)) {
				$repoIngrediente->assoc_alergenos($ingredienteCreado->getId(), $alergenos);
			}

			// Respuesta es siempre JSON
			http_response_code(200);
			echo json_encode([
				'success' => true,
				'message' => 'Ingrediente creado exitosamente.',
				'redirect_url' => '/ingredientes', // URL de redirección
				'ingrediente' => $ingredienteCreado->getAsArray() // Ingrediente recién creado como array asociativo
			]);
			exit;
		} else {
			// Si la solicitud no es válida
			http_response_code(400);
			echo json_encode(['error' => 'No se ha enviado la imagen o los datos correctamente.']);
			exit;
		}
	}

	public function updateIngredients($data) {

		$validator = new Validator(); // Instanciamos el validador
		$repoIngrediente = new RepoIngrediente();

		// Recuperar el ingrediente actual
		$ingredienteActual = $repoIngrediente->getById($data['id']);

		// Definir las reglas de validación
		$camposRequeridos = [
			'nombre' => 'Requerido', // nombre es obligatorio
			'precio' => 'Requerido|Numerico' // precio es obligatorio y debe ser un número
		];

		// Validar los campos obligatorios y las validaciones personalizadas
		$errores = $validator->validarCampos($data, $camposRequeridos);

		// Si el nombre del ingrediente no cambia, no validamos duplicados
		if ($ingredienteActual->getNombre() != $data['nombre']) {
			// Aquí se valida si el nombre es único solo si es diferente al actual
			if ($validator->validarDuplicado('nombre', $data['nombre'], $repoIngrediente)) {
				$errores['nombre'] = 'El nombre ya está registrado.';
			}
		}

		// Si hay errores, devolvemos la respuesta con los errores encontrados
		if (count($errores) > 0) {
			http_response_code(400); // Código HTTP 400 para errores de validación
			return json_encode(['errores' => $errores]);
		}

		// Verificar que se está haciendo una petición POST
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405); // Código de error: método no permitido
			return json_encode(['error' => 'Método no permitido.']);
		}

		// Verificar que el parámetro 'id' está presente
		if (!isset($data['id']) || empty($data['id'])) {
			http_response_code(400); // Código de error: petición incorrecta
			return json_encode(['error' => 'ID del ingrediente no especificado.']);
		}

		// Verificar que se han enviado los datos necesarios
		if (!isset($data['nombre']) || !isset($data['precio'])) {
			http_response_code(400); // Código de error: petición incorrecta
			return json_encode(['error' => 'Faltan datos obligatorios.']);
		}

		// Recuperar los valores necesarios
		$ingrediente_id = $data['id'];
		$nombre = $data['nombre'];
		$precio = $data['precio'];
		$alergenos = isset($data['alergenos']) ? json_decode($data['alergenos'], true) : [];

		// Verificar si alergenos es un array
		if (!is_array($alergenos)) {
			$alergenos = [];
		}

		// Si se recibe una foto, procesarla
		if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
			$foto = $_FILES['foto'];

			// Guardar la foto en el directorio de imágenes
			$directorioDestino = $_SERVER['DOCUMENT_ROOT'] . '/img/ingredientes/';
			$nombreOriginal = basename($foto['name']);
			$rutaDestino = $directorioDestino . $nombreOriginal;

			// Si ya existe un archivo con el mismo nombre, renombrarlo
			if (file_exists($rutaDestino)) {
				$i = 1;
				$ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$nombreArchivo = pathinfo($nombreOriginal, PATHINFO_FILENAME); // Obtener el nombre del archivo sin la extensión

				// Generar nuevo nombre con sufijo numérico hasta que el archivo no exista
				do {
					$nombreArchivo = pathinfo($nombreOriginal, PATHINFO_FILENAME) . '-' . $i . '.' . $ext;
					$rutaDestino = $directorioDestino . $nombreArchivo;
					$i++; // Incrementar el contador
				} while (file_exists($rutaDestino)); // Comprobar si el nuevo nombre ya existe
			} else {
				$nombreArchivo = $nombreOriginal;
			}

			// Mover el archivo al destino
			if (!move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
				http_response_code(400);
				return json_encode(['error' => 'Error al guardar la imagen.']);
			}

			// Ruta relativa a la imagen
			$rutaRelativa = '/img/ingredientes/' . $nombreArchivo;
		} else {
			// Si no se recibe una foto, mantener la ruta actual o valor por defecto
			$rutaRelativa = $data['foto'] ?? ''; // Si no se recibe, usar la foto existente o dejar en blanco
		}

		// Crear el objeto Ingrediente
		$repoIngrediente = new RepoIngrediente();
		$ingrediente = new Ingrediente(
			$ingrediente_id, // El ID ya existe y se pasa para actualizar
			$nombre,
			$rutaRelativa,
			$precio,
			$alergenos // Los alérgenos nuevos
		);

		try {
			// Actualizar el ingrediente
			$ingredienteActualizado = $repoIngrediente->update($ingrediente);

			// Si todo ha ido bien, enviar la respuesta exitosa
			http_response_code(200);
			return json_encode([
				'success' => true,
				'message' => 'Ingrediente actualizado exitosamente.',
				'redirect_url' => '/ingredientes',
				'ingrediente' => $ingredienteActualizado->getAsArray() // Ingrediente actualizado
			]);

		} catch (Exception $e) {
			// Si algo sale mal, enviar la respuesta con error
			http_response_code(500); // Error de servidor interno
			return json_encode(['error' => 'Error al actualizar el ingrediente: ' . $e->getMessage()]);
		}
	}

	public function deleteIngredients($data) {
		// Verificar que el parámetro 'id' ha sido enviado
		if (!isset($data['id']) || empty($data['id'])) {
			http_response_code(400); // Código de error: petición incorrecta
			return json_encode(['error' => 'ID del ingrediente no especificado.']);
		}

		// Obtener el ID del ingrediente a eliminar
		$ingrediente_id = $data['id'];

		// Instanciar el repositorio
		$repoIngrediente = new RepoIngrediente();

		// Llamar al método delete del repositorio
		$result = $repoIngrediente->delete($ingrediente_id);

		if ($result) {
			// Si la eliminación fue exitosa, responder con código 200
			http_response_code(200);
			return json_encode([
				'success' => true,
				'message' => 'Ingrediente eliminado exitosamente.',
				'redirect_url' => '/ingredientes',
			]);

		} else {
			// Si hubo un error, devolver error con código 500 (internal server error)
			http_response_code(500);
			return json_encode(['error' => 'Error al eliminar el ingrediente.']);
		}
	}

	// Método para generar el PDF con los alérgenos
	public function generatePdf($data) {
		// Asegurarse de que los alérgenos están presentes
		if (empty($_POST['alergenos'])) {
			http_response_code(400);
			echo json_encode(['error' => 'No se han enviado alérgenos.']);
			exit;
		}

		// Decodificar los alérgenos (ya que se enviaron como un array de objetos JSON)
		$alergenos = [];
		foreach ($_POST['alergenos'] as $alergenoJson) {
			$alergenos[] = json_decode($alergenoJson, true);  // Decodificamos cada alérgeno, true indica que queremos un array asociativo
//			[
//				['nombre' => 'Gluten', 'foto' => '/img/gluten.jpg'],
//				['nombre' => 'Leche', 'foto' => '/img/leche.jpg']
//			]
		}

		// Instanciar y configurar DOMpdf
		$dompdf = new Dompdf();

		// Construir el HTML para el PDF con una tabla
		$html = '<html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 20px;
                        }
                        h1 {
                            text-align: center;
                            color: #333;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 20px;
                        }
                        table, th, td {
                            border: 1px solid black;
                        }
                        th, td {
                            padding: 8px;
                            text-align: left;
                        }
                        th {
                            background-color: #f2f2f2;
                        }
                    </style>
                </head>
                <body>
                    <h1>Listado de Alergenos</h1>
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>';

		// Rellenar la tabla con los alérgenos
		foreach ($alergenos as $alergeno) {
			// Ruta absoluta de la imagen
			$imagePath = $_SERVER['DOCUMENT_ROOT'] . $alergeno['foto'];  // Usamos $_SERVER['DOCUMENT_ROOT'] para obtener la ruta absoluta - $_SERVER['DOCUMENT_ROOT'] . '/img/ingredientes/nombre.jpg';

			// Verificamos si la imagen existe en el servidor
			if (file_exists($imagePath)) {
				// Leer el archivo de la imagen y convertirlo a base64
				$imageData = base64_encode(file_get_contents($imagePath));
				$imageSrc = 'data:image/png;base64,' . $imageData;  // Asumimos que la imagen es PNG (puedes cambiar el tipo si es necesario)
			} else {
				// Si no se encuentra la imagen, usamos una imagen por defecto o dejamos el src vacío
				$imageSrc = '';  // Aquí puedes poner una URL de imagen por defecto si prefieres
			}

			// Agregar la fila de la tabla
			$html .= '<tr>
                    <td>' . htmlspecialchars($alergeno['nombre']) . '</td>';

			// Verificar si se pudo convertir la imagen a base64
			if ($imageSrc) {
				$html .= '<td><img src="' . $imageSrc . '" width="50" height="50" alt="' . htmlspecialchars($alergeno['nombre']) . '"></td>';
			} else {
				$html .= '<td>No disponible</td>';
			}

			$html .= '</tr>';
		}

		// Cerrar la tabla y el cuerpo del HTML
		$html .= '   </tbody>
                </table>
            </body>
        </html>';

		// Cargar el HTML en Dompdf
		$dompdf->loadHtml($html);

		// Configurar el tamaño y orientación del papel
		$dompdf->setPaper('A4', 'portrait');

		// Renderizar el PDF
		$dompdf->render();

		// Obtener el contenido binario del PDF
		$pdfContent = $dompdf->output();

		// Enviar el PDF al navegador
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="alergenos.pdf"');
		echo $pdfContent;
		exit;
	}


}