<?php $this->layout('master'); ?>
<?php $this->start('css'); ?>

<link rel="stylesheet" href="stylesRegister.css">

<?php $this->stop() ?>

<?php $this->start('header') ?>
<?php $this->stop() ?>
<!-- Mostrar el mensaje flash, si existe -->
<?php if (isset($message)): ?>
	<div class="flash-message <?= htmlspecialchars($message['type']) ?>" id="flashMessage">
		<?= htmlspecialchars($message['message']) ?>
	</div>
<?php endif; ?>

<?php $this->start('formulario') ?>
<div class="form-wrapper">
	<div class="form-container">
		<h2>Formulario de Registro</h2>
		<form action="/register-post" method="POST" class="formulario__register">

			<div class="form-fielsets">
				<!-- Datos Personales -->
				<fieldset>
					<legend>Datos Personales</legend>

					<label for="nombre">Nombre:</label>
					<input type="text" name="nombre" placeholder="Tu nombre" value="<?= isset($data['nombre']) ? $data['nombre'] : ''; ?>">
					<?php if (isset($errores['nombre'])) : ?>
						<span class="error-form"><?= $errores['nombre']; ?></span>
					<?php endif; ?>

					<label for="email">Correo Electrónico:</label>
					<input type="email" name="email" placeholder="tu.email@ejemplo.com" value="<?= isset($data['email']) ? $data['email'] : ''; ?>">
					<?php if (isset($errores['email'])) : ?>
						<span class="error-form"><?= $errores['email']; ?></span>
					<?php endif; ?>

					<label for="dni">DNI:</label>
					<input type="text" name="dni" placeholder="DNI" value="<?= isset($data['dni']) ? $data['dni'] : ''; ?>" required>
					<?php if (isset($errores['dni'])) : ?>
						<span class="error-form"><?= $errores['dni']; ?></span>
					<?php endif; ?>

					<label for="contrasenna">Contraseña:</label>
					<input type="password" name="contrasenna" placeholder="Crea una contraseña" value="<?= isset($data['contrasenna']) ? $data['contrasenna'] : ''; ?>">
					<?php if (isset($errores['contrasenna'])) : ?>
						<span class="error-form"><?= $errores['contrasenna']; ?></span>
					<?php endif; ?>
				</fieldset>

				<!-- Dirección -->
				<fieldset>
					<legend>Dirección</legend>

					<label for="localidad">Localidad:</label>
					<input type="text" name="localidad" placeholder="Localidad" value="<?= isset($data['localidad']) ? $data['localidad'] : ''; ?>">
					<?php if (isset($errores['localidad'])) : ?>
						<span class="error-form"><?= $errores['localidad']; ?></span>
					<?php endif; ?>

					<label for="calle">Calle:</label>
					<input type="text" name="calle" placeholder="Calle de residencia" value="<?= isset($data['calle']) ? $data['calle'] : ''; ?>">
					<?php if (isset($errores['calle'])) : ?>
						<span class="error-form"><?= $errores['calle']; ?></span>
					<?php endif; ?>

					<label for="numero">Número:</label>
					<input type="text" name="numero" placeholder="Número de tu domicilio" value="<?= isset($data['numero']) ? $data['numero'] : ''; ?>">
					<?php if (isset($errores['numero'])) : ?>
						<span class="error-form"><?= $errores['numero']; ?></span>
					<?php endif; ?>
				</fieldset>

			</div>

			<!-- Botón de registro -->
			<button class="btn"  type="submit">Registrarse</button>
		</form>
	</div>
</div>

<?php $this->stop() ?>

<?php $this->start('scripts'); ?>

<script>

	document.addEventListener('DOMContentLoaded', () => {
		const formulario 	= document.querySelector('.formulario__register');
		const campos 		= formulario.querySelectorAll('input');
		const submitButton 	= formulario.querySelector('button[type="submit"]');
		let camposValidados = new Set();  // Usamos un Set para almacenar los campos validados correctamente

		// Añadimos el evento 'change' en lugar de 'input' o 'keyup'
		campos.forEach(campo => {
			campo.addEventListener('change', () => {
				validarCampo(campo);
				actualizarEstadoFormulario();
			});
		});

		// Función para validar un solo campo
		async function validarCampo(campo) {
			try {
				const formData = new FormData();
				formData.append('action', 'validate');  // Tipo de acción
				formData.append(campo.name, campo.value); // Asegúrate de agregar el campo correcto

				console.log("Datos enviados a la API:", [...formData.entries()]);  // Verificación

				// Hacemos la llamada a la API y esperamos la respuesta
				const response = await fetch('/apiRegister', { method: 'POST', body: formData });

				// Esperamos la respuesta JSON
				const data = await response.json();

				console.log("Datos recibidos:", data);  // Verificación de datos recibidos

				// Limpiamos cualquier error previo
				limpiarErrores(campo);

				// Verificamos si la validación fue exitosa (basado en data.success)
				if (data.success) {
					// Si la validación fue exitosa, agregamos el campo al conjunto de campos validados
					camposValidados.add(campo.name);
				} else if (data.errores && data.errores[campo.name]) {
					// Si hay errores en el campo, mostramos el error y eliminamos del conjunto
					mostrarError(campo, data.errores[campo.name]);
					camposValidados.delete(campo.name);
				}

				// Actualizamos el estado del formulario tras la validación
				actualizarEstadoFormulario();

			} catch (error) {
				console.error('Error al validar el campo:', error);  // Manejamos cualquier error de la solicitud
			}
		}

		// Función para limpiar los errores de un campo
		function limpiarErrores(campo) {
			const errorElement = campo.nextElementSibling;
			if (errorElement && errorElement.classList.contains('error-form')) {
				errorElement.remove();
			}
		}

		// Función para mostrar un mensaje de error debajo del campo
		function mostrarError(campo, mensajeError) {
			const newErrorElement = document.createElement('span');
			newErrorElement.classList.add('error-form');
			newErrorElement.textContent = mensajeError;
			campo.parentNode.insertBefore(newErrorElement, campo.nextSibling);
		}

		// Función para actualizar el estado del formulario (habilitar o deshabilitar el botón de submit)
		function actualizarEstadoFormulario() {
			let formIsValid = true;
			// Verificamos si todos los campos han sido validados correctamente
			campos.forEach(campo => {
				if (!campo.value || !camposValidados.has(campo.name) || campo.nextElementSibling?.classList.contains('error-form')) {
					formIsValid = false;
				}
			});
			submitButton.disabled = !formIsValid;
		}

		// Función para manejar el envío del formulario
		formulario.addEventListener('submit', async (event) => {
			event.preventDefault();

			if (await validarFormulario()) {
				const formData = new FormData(formulario);
				formData.append('action', 'register');

				try {
					const response = await fetch('/apiRegister', { method: 'POST', body: formData });

					// Verificamos si la respuesta es exitosa antes de intentar convertirla a JSON
					if (!response.ok) {
						throw new Error('Error en la respuesta del servidor. Código de estado: ' + response.status);
					}

					// Intentamos obtener la respuesta como texto primero
					const responseText = await response.text();

					if (!responseText) {
						throw new Error('La respuesta del servidor está vacía.');
					}

					// Depuración: Verificar el contenido de la respuesta antes de convertirla en JSON
					console.log('Respuesta del servidor como texto:', responseText);

					// Intentamos convertir la respuesta a JSON
					let data;
					try {
						data = JSON.parse(responseText);
					} catch (error) {
						throw new Error('La respuesta no es un JSON válido.');
					}

					console.log('Respuesta del servidor como JSON:', data);

					// Verificamos si la respuesta contiene un mensaje de éxito
					if (data.success) {
						// Si hay una URL de redirección, redirigimos al usuario
						if (data.redirect_url) {
							// Guardamos el mensaje en sessionStorage antes de redirigir
							sessionStorage.setItem('flash_message', JSON.stringify({
								message: data.message,
								type: 'success'  // O el tipo que consideres
							}));
							// Además guardamos el ID del usuario en sessionStorage
							// sessionStorage.setItem('user_id', data.id);  // Guardamos el ID
							window.location.href = data.redirect_url;  // Redirige al usuario a la URL proporcionada
							return;
						} else {
							alert(data.message);  // Muestra el mensaje de éxito si no hay URL de redirección
						}
						formulario.reset();  // Limpiamos el formulario
					} else if (data.errores) {
						// Si hay errores, los mostramos
						mostrarErrores(data.errores);
					} else {
						// Si la respuesta tiene errores, los mostramos
						sessionStorage.setItem('flash_message', JSON.stringify({
							message: data.message || 'Algo ha fallado. Intenta nuevamente.',
							type: 'error'
						}));

						// Redirigimos a la URL que indica la API
						window.location.href = data.redirect_url || '/register';
						return;
					}
				} catch (error) {
					// Guardar el mensaje en sessionStorage
					sessionStorage.setItem('flash_message', JSON.stringify({
						message: 'Hubo un problema al registrar el usuario. Intenta nuevamente.',
						type: 'error'  // También puedes usar 'warning', 'info', 'success', etc.
					}));
					// Redirigimos a la URL que indica la API
					window.location.href = '/register';
					return;
				}
			} else {
				alert('Por favor, corrige los errores antes de enviar el formulario.');
			}
		});



		// Función para validar todos los campos antes de enviar
		async function validarFormulario() {
			// Ejecutamos la validación de cada campo y esperamos que todas las validaciones terminen
			const validaciones = Array.from(campos).map(campo => validarCampo(campo));
			await Promise.all(validaciones);
			return camposValidados.size === campos.length;
		}

		// Función para mostrar todos los errores al enviar
		function mostrarErrores(errores) {
			for (const campo in errores) {
				const input = formulario.querySelector(`[name="${campo}"]`);
				if (input) {
					mostrarError(input, errores[campo]);
				}
			}
		}

		// Habilitar el botón de submit al principio
		actualizarEstadoFormulario();
	});

</script>

<?php $this->stop(); ?>
