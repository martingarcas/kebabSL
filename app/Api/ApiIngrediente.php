<?php

namespace App\Api;

use App\Models\Ingrediente;
use App\Repositorios\RepoAlergeno;
use App\Repositorios\RepoIngrediente;
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
			$alergenos[] = json_decode($alergenoJson, true);  // Decodificamos cada alérgeno
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
			$imagePath = $_SERVER['DOCUMENT_ROOT'] . $alergeno['foto'];  // Usamos $_SERVER['DOCUMENT_ROOT'] para obtener la ruta absoluta

			// Verificamos si la imagen existe en el servidor
			if (file_exists($imagePath)) {
				// Leer el archivo de la imagen y convertirlo a base64
				$imageData = base64_encode(file_get_contents($imagePath));
				$imageSrc = 'data:image/jpeg;base64,' . $imageData;  // Asumimos que la imagen es PNG (puedes cambiar el tipo si es necesario)
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