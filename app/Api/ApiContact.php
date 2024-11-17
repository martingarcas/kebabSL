<?php


namespace App\Api;


use PHPMailer\PHPMailer\PHPMailer;

class ApiContact {

	public function handleRequest($data) {
		header("Access-Control-Allow-Origin: *"); // Permitir todas las solicitudes
		header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Permitir POST y GET
		header("Access-Control-Allow-Headers: Content-Type, Authorization");
		header('Content-Type: application/json');

		// Decidir qué método invocar según el valor de 'action'
		if (isset($data['action'])) {
			switch ($data['action']) {
				case 'sendContact':
					return $this->sendContact($data);
				default:
					http_response_code(400);
					return json_encode(['error' => 'Acción no válida.']);
			}
		} else {
			http_response_code(400);
			return json_encode(['error' => 'Acción no especificada.']);
		}
	}

	public function sendContact(){

		$post = $_POST;

		//filter information and validate
		//prevent php inyection
		//prevent execute command
		//prevent sql injection

		$validateEmail = preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $post['email']) ? $post['email'] : false;

		$validateName = preg_match("/^[a-zA-Z-áéíóú]+(([',. -][a-zA-Z-áéíóú ])?[a-zA-Z-áéíóú]*)*$/", $post['name']) ? $post['name'] : false;

		// $validateLastName = preg_match("/^[a-zA-Z-áéíóú]+(([',. -][a-zA-Z-áéíóú ])?[a-zA-Z-áéíóú]*)*$/", $post['lastname']) ? $post['lastname'] : false;

		$validateTlfn = preg_match("/^((?:[1-9][0-9 ().-]{5,28}[0-9])|(?:(00|0)( ){0,1}[1-9][0-9 ().-]{3,26}[0-9])|(?:(\+)( ){0,1}[1-9][0-9 ().-]{4,27}[0-9]))$/",
			$post['telephone']) ? $post['telephone'] : false;

		$validateComment = trim($post['comment']) ? $post['comment'] : false;

		$validateCheckPolitics = $post['politics'] = true ? $post['politics'] : false;

		/* $validateCheckSuscribe = $post['suscribe'] = true ? $post['suscribe'] : false; */

		$response = [
			'type' 	=> '',
			'message' 	=> '',
		];

		// aquí añadimos los requireds, si faltan estos datos, no se enviará el formulario.
		if (!$validateEmail || !$validateName || !$validateCheckPolitics) {

			$response = [
				'type' 	=> 'error',
				'message' 	=> 'Faltan datos por rellenar',
			];


			echo json_encode($response);
			//se puede filtrar la ip y guardarlo en un log file_put_contents
		}

		// Si todos los datos son correctos, se hace una última validación y se envía.
		if (isset($response['type']) && $response['type'] != 'error') {

			/* 	if(!$validateCheckSuscribe) {
					$validateCheckSuscribe = "No aceptada";
				}else{
					$validateCheckSuscribe = "Aceptada";
				}
			 */
			$dataSend = [
				'name' 		=> trim($validateName),
				'email' 	=> trim($validateEmail),
				'telephone' => $this->test_input($validateTlfn),
				'comment' 	=> $this->test_input($validateComment),
				'politics' 	=> $this->test_input($validateCheckPolitics),
			];

			//send email
			$this->send_mail($dataSend);
			//todo: clasecita wapa pequeñita para phpmailer


			$response = [
				'type' => 'success',
				'message' => '¡El mensaje ha sido enviado con exito!',
				'redirect_url' => '/login'
			];
		}

		http_response_code(200);
		echo json_encode($response);

	}

	function send_mail($data) {

		$name 		= $data["name"];
		$email 		= $data["email"];
		$telephone 	= $data["telephone"];
		$comment 	= $data["comment"];
		$politics 	= $data["politics"];
		// $suscribe = $data["suscribe"];

		$mail = new PHPMailer(true);

		try {
			// S E R V E R _ S E T T I N G

			// $mail->SMTPDebug = SMTP::DEBUG_SERVER;           				//Enable verbose debug output
			$mail->isSMTP();                                    				//Send using SMTP
			$mail->Host       = 'smtp.gmail.com';               				//Set the SMTP server to send through
			$mail->SMTPAuth   = TRUE;                           				//Enable SMTP authentication
			$mail->Username   = 'lealrodrigobose@gmail.com';     										//SMTP username
			$mail->Password   = 'ouoaqrzdgraaercv';							    				//SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    				//Enable implicit TLS encryption
			$mail->Port       = 465;                        				//TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

			//R E C I P I E N T S

			$mail->setFrom('lealrodrigobose@gmail.com', 'Mailer');					//RECEPTOR 			oficina.rehabilitacion@avanzasi.es
			$mail->addAddress('lealrodrigobose@gmail.com', 'Martin infor'); 		//Add a recipient
			//$mail->addAddress('ellen@example.com');               			//Name is optional
			//$mail->addReplyTo('info@example.com', 'Information');
			//$mail->addCC('cc@example.com');
			//$mail->addBCC('bcc@example.com');

			// A T T A C H M E N T

			//$mail->addAttachment('/var/tmp/file.tar.gz');         			//Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    			//Optional name

			// C O N T E N T
			$mail->isHTML(true);                                  				//Set email format to HTML
			$mail->Subject = "DONER KEBAB SL: $name $telephone"; // Asunto

			//TODO:crear plantilla bonica
			// Cuerpo del correo que llegará al revisor del formulario
			$mail->Body    =	"Nombre: $name<br>
							Email: $email<br>
							Teléfono: $telephone<br><br>
							Mensaje:<br>$comment<br><br>
							¿Politicas Aceptadas?: $politics <br><br>";


			$mail->AltBody = "Done Kebab S.L. contacto";

			$mail->send();

			return true;
			// echo 'El mensaje ha sido enviado con éxito';
		} catch (Exception $e) {
			// FOR DEBUG
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

}