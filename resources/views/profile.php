<?php $this->layout('master'); ?>
<?php $this->start('css'); ?>

	<link rel="stylesheet" href="stylesProfile.css">
<?php $this->stop() ?>

<?php $this->start('formulario') ?>
<div class="profile-wrapper">
		<!-- Aquí se mostrarán los datos cargados -->
		<div class="form-user-cnt" id="user-data">
			<div class="form-user-inner" id="form-user-inner">

				<fieldset class="header-ingredient">

					<div class="section-box">
						<div class="foto-container" id="foto-container">
							<input type="file" id="foto" name="foto" accept="image/*">
							<img src="/img/iconos/agregar.png" alt="Agregar imagen" class="icono-agregar">
							<div id="foto-preview"></div>
						</div>

						<button type="button" id="camera-button" title="Tomar una foto">Tomar foto</button>
						<video id="player" autoplay style="display:none;"></video>
						<button type="button" id="capture-button" style="display:none;">Capturar</button>

					</div>


					<div class="section-box">
						<!-- Nombre -->
						<table id="table-section">

							<thead id="button-section">

							<tr>
								<th id="button2" scope="col">Nombre</th>
								<th id="button2" scope="col">Acciones</th>
							</tr>

							</thead>

							<tbody id="body-table">

							<tr>
								<td id="td-nombre">Martín</td>
								<td>
									<button>Editar</button>
								</td>
							</tr>

							</tbody>

						</table>

						<!-- Apellido1 -->
						<table id="table-section">

							<thead id="button-section">

							<tr>
								<th id="button2" scope="col">Primer Apellido</th>
								<th id="button2" scope="col">Acciones</th>
							</tr>

							</thead>

							<tbody id="body-table">

							<tr>
								<td id="td-apellido1">García</td>
								<td>
									<button>Editar</button>
								</td>
							</tr>

							</tbody>

						</table>

						<!-- Apellido2 -->
						<table id="table-section">

							<thead id="button-section">

							<tr>
								<th id="button2" scope="col">Segundo Apellido</th>
								<th id="button2" scope="col">Acciones</th>
							</tr>

							</thead>

							<tbody id="body-table">

							<tr>
								<td id="td-apellido2">Castillo</td>
								<td>
									<button>Editar</button>
								</td>
							</tr>

							</tbody>

						</table>

					</div>

					<div class="section-box">
						<!-- Email -->
						<table id="table-section">

							<thead id="button-section">

							<tr>
								<th id="button2" scope="col">Email</th>
								<th id="button2" scope="col">Acciones</th>
							</tr>

							</thead>

							<tbody id="body-table">

							<tr>
								<td id="td-email">martin@gmail.com</td>
								<td>
									<button>Editar</button>
								</td>
							</tr>

							</tbody>

						</table>

						<!-- DNI -->
						<table id="table-section">

							<thead id="button-section">

							<tr>
								<th id="button2" scope="col">DNI</th>
								<th id="button2" scope="col">Acciones</th>
							</tr>

							</thead>

							<tbody id="body-table">

							<tr>
								<td id="td-dni">77383320G</td>
								<td>
									<button>Editar</button>
								</td>
							</tr>

							</tbody>

						</table>

						<!-- Telefono -->
						<table id="table-section">

							<thead id="button-section">

							<tr>
								<th id="button2" scope="col">Teléfono</th>
								<th id="button2" scope="col">Acciones</th>
							</tr>

							</thead>

							<tbody id="body-table">

							<tr>
								<td id="td-telefono">693209523</td>
								<td>
									<button>Editar</button>
								</td>
							</tr>

							</tbody>

						</table>

					</div>
				</fieldset>
			</div>
		</div>
</div>

<?php $this->stop() ?>

<?php $this->start('scripts'); ?>

	<script>

		document.addEventListener('DOMContentLoaded', () => {
			const profileContainer = document.getElementById('profile-data');
			const fotoInput = document.querySelector('#foto');
			const fotoContainer = document.querySelector('#foto-container');
			const fotoPreview = document.querySelector('#foto-preview');
			const cameraButton = document.querySelector('#camera-button');
			const captureButton = document.querySelector('#capture-button');
			const player = document.querySelector('#player');

			// Añadir los eventos change para cada campo de entrada
			fotoInput.addEventListener('change', (event) => {
				const file = event.target.files[0];
				if (file) {
					const reader = new FileReader();
					reader.onload = function (e) {
						fotoPreview.innerHTML = `<img src="${e.target.result}" alt="Imagen seleccionada" class="foto-preview-img">`;
					};
					reader.readAsDataURL(file);
				}
			});

			// Abrir el selector de imagen al hacer clic en el contenedor de foto
			fotoContainer.addEventListener('click', () => {
				fotoInput.click();
			});

			// Función para capturar la foto desde la cámara
			captureButton.addEventListener('click', () => {
				const imageWidth = player.videoWidth; // Usar dimensiones reales del video
				const imageHeight = player.videoHeight;

				// Crear un canvas oculto para capturar la imagen
				const outputCanvas = document.createElement('canvas');
				outputCanvas.width = imageWidth;
				outputCanvas.height = imageHeight;
				const context = outputCanvas.getContext('2d');
				context.drawImage(player, 0, 0, imageWidth, imageHeight);

				// Convertir la imagen capturada a Blob
				outputCanvas.toBlob((blob) => {
					capturedBlob = blob;

					// Crear un objeto URL para mostrar la imagen en la vista previa
					const url = URL.createObjectURL(blob);
					fotoPreview.innerHTML = `<img src="${url}" alt="Foto capturada" class="foto-preview-img">`;

					// Mostrar nuevamente el botón de la cámara
					cameraButton.style.display = 'block';
					captureButton.style.display = 'none';

					// Detener el video
					const stream = player.srcObject;
					const tracks = stream.getTracks();
					tracks.forEach((track) => track.stop());
					player.srcObject = null;
				}, 'image/jpeg'); // Especificar el formato de la imagen
			});

			// Hacer clic en el botón de cámara para empezar a usarla
			cameraButton.addEventListener('click', () => {
				startCamera();  // Iniciar la cámara cuando el usuario haga clic en el botón
			});

			// Asignar eventos a botones de edición de fila
			document.querySelectorAll('button').forEach(button => {
				button.addEventListener('click', function (event) {
					editarFila(event.target);
				});
			});

			// Función para iniciar la cámara
			function startCamera() {
				navigator.mediaDevices
					.getUserMedia({ video: true })
					.then((stream) => {
						player.srcObject = stream;

						// Mostrar el video dentro del contenedor foto-preview
						fotoPreview.innerHTML = '';
						fotoPreview.appendChild(player);
						// Ajustar estilos del video para ocupar el contenedor
						player.style.display = 'block';
						player.style.width = '100%';
						player.style.height = '100%';

						cameraButton.style.display = 'none';
						captureButton.style.display = 'block';  // Mostrar el botón para capturar
					})
					.catch((error) => {
						console.error('No se puede acceder a la cámara...', error);
					});
			}

			// Realizar una petición para obtener los datos del usuario
			async function fetchUserData() {
				try {
					const formData = new FormData();
					formData.append('action', 'loadUser');

					const response = await fetch('/apiUser', {
						method: 'POST',
						body: formData
					});

					const data = await response.json();

					if (response.ok && data.success) {
						renderUserProfile(data.usuario);
					} else {
						console.log(data.error || 'Error desconocido.');
					}
				} catch (error) {
					console.log('Error en la conexión con el servidor.');
				} finally {
				}
			}

			// Realizar una petición para actualizar un campo
			async function guardarCampo(input, campo) {
				const valor = input.value.trim(); // Obtener el valor del input
				const fila = input.closest('tr'); // Obtener la fila asociada

				// Llamar a validarCampo para realizar todas las validaciones locales
				validarCampo(input);

				// Verificar si hay errores locales (mensaje de error en el input)
				if (input.nextElementSibling && input.nextElementSibling.textContent !== '') {
					return; // Si hay errores, detenemos el flujo
				}

				// Enviar el dato al backend
				try {
					// Obtener el ID del usuario desde sessionStorage
					let userId = sessionStorage.getItem('user_id');
					const formData = new FormData();
					formData.append('action', 'updateUser');
					formData.append('id', userId);
					formData.append('campo', campo); // Enviar el nombre del campo (e.g., 'email', 'dni')
					formData.append('valor', valor); // Enviar el valor del input

					const response = await fetch('/apiUser', {
						method: 'POST',
						body: formData,
					});

					const data = await response.json();

					console.log(data.success)
					if (response.ok && data.success) {
						console.log(`El campo "${campo}" se guardó correctamente.`);
						// Actualizar la fila con el nuevo valor y restaurar el diseño original
						fila.querySelector(`#td-${campo}`).textContent = data.valorActualizado || valor;
						restaurarBotonEditar(fila);

					} else {
						mostrarError(input, data.error || 'Error desconocido');
					}
				} catch (error) {
					mostrarError(input, 'Error al conectar con el servidor');
					console.error('Error:', error);
				}
			}

			// Renderizar los datos del usuario en la página
			function renderUserProfile(user) {
				// Comprobar que las claves existen en el objeto antes de asignar
				document.getElementById('td-nombre').textContent = user.nombre || '-';
				document.getElementById('td-apellido1').textContent = user.apellido1 || '-';
				document.getElementById('td-apellido2').textContent = user.apellido2 || '-';
				document.getElementById('td-email').textContent = user.email || '-';
				document.getElementById('td-dni').textContent = user.dni || '-';
				document.getElementById('td-telefono').textContent = user.telefono || '-';
			}

			// Función para editar la fila y habilitar la validación individual de cada campo
			function editarFila(button) {
				if (!button || !button.parentNode || !button.parentNode.parentNode) {
					console.error('El botón o su fila asociada no se pudieron encontrar.');
					return;
				}

				let fila = button.parentNode.parentNode; // Obtener la fila del botón clicado

				if (!fila.editadaMartin) {
					fila.editadaMartin = true; // Marcar la fila como editada
					let valores = []; // Guardar valores originales
					let celdas = fila.cells; // Todas las celdas de la fila

					// Iterar sobre las celdas y reemplazar el contenido por inputs
					for (let i = 0; i < celdas.length - 1; i++) {
						let celda = celdas[i];
						let valorActual = celda.textContent.trim();

						let input = document.createElement('input');
						input.type = 'text';
						input.value = valorActual;

						input.addEventListener('input', () => validarCampo(input))

						valores.push(valorActual); // Guardar el valor actual
						celda.innerHTML = '';
						celda.appendChild(input); // Reemplazar el contenido por el input
					}

					fila.valoresOriginales = valores; // Guardar los valores originales en la fila

					// Reemplazar el botón de editar por los de guardar y cancelar
					let botonGuardar = document.createElement('button');
					botonGuardar.textContent = 'Guardar';
					botonGuardar.className = 'btn-guardar';
					botonGuardar.style.marginRight = '4px';
					botonGuardar.addEventListener('click', () => {
						const input = fila.querySelector('input'); // Obtener el input de la fila
						const campo = input.parentNode.id.split('-')[1]; // Identificar el campo por su ID
						guardarCampo(input, campo); // Llamar a la función guardarCampo con el input y el campo
					});

					let botonCancelar = document.createElement('button');
					botonCancelar.textContent = 'Cancelar';
					botonCancelar.className = 'btn-cancelar';
					botonCancelar.addEventListener('click', () => cancelarEdicion(fila, valores));

					// Eliminar el botón "Editar" y agregar los nuevos botones
					let celdaBoton = fila.cells[fila.cells.length - 1];
					celdaBoton.innerHTML = ''; // Limpiar la celda
					celdaBoton.appendChild(botonGuardar);
					celdaBoton.appendChild(botonCancelar);
				}
			}

			// Función para cancelar la edición y restaurar los valores originales
			function cancelarEdicion(fila, valoresOriginales) {
				let celdas = fila.cells;

				// Restaurar los valores originales en las celdas
				for (let i = 0; i < valoresOriginales.length; i++) {
					celdas[i].textContent = valoresOriginales[i];
				}

				// Restaurar el botón "Editar"
				let celdaBoton = celdas[celdas.length - 1];
				celdaBoton.innerHTML = ''; // Limpiar la celda
				let botonEditar = document.createElement('button');
				botonEditar.textContent = 'Editar';
				botonEditar.addEventListener('click', () => editarFila(botonEditar));
				celdaBoton.appendChild(botonEditar);

				fila.editadaMartin = false; // Marcar la fila como no editada
			}

			// Función para restaurar el botón "Editar" después de guardar los cambios
			function restaurarBotonEditar(fila) {
				let celdas = fila.cells;

				// Restaurar el botón "Editar" en la última celda de la fila
				let celdaBoton = celdas[celdas.length - 1];
				celdaBoton.innerHTML = ''; // Limpiar la celda
				let botonEditar = document.createElement('button');
				botonEditar.textContent = 'Editar';
				botonEditar.addEventListener('click', () => editarFila(botonEditar));
				celdaBoton.appendChild(botonEditar);

				fila.editadaMartin = false; // Marcar la fila como no editada
			}

			// Función para validar el email
			function validarEmail(input) {
				let value = input.value.trim();
				const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

				if (value === '') {
					mostrarError(input, 'El email es obligatorio');

				} else if (!emailRegex.test(value)) {
					mostrarError(input, 'Formato no válido');
				} else {
					ocultarError(input);
				}
			}

			// Función para validar el DNI
			function validarDni(input) {
				let value = input.value.trim();
				const dniRegex = /^[0-9]{8}[A-Za-z]{1}$/;
				if (value === '') {
					mostrarError(input, 'El DNI es obligatorio');
				} else if (!dniRegex.test(value)) {
					mostrarError(input, 'Formato no válido');
				} else {
					ocultarError(input);
				}
			}
			
			function validarCampo(input) {
				let campo = input.parentNode.id.split('-')[1];

				if (campo == 'email') {
					validarEmail(input);
				} else if (campo == 'dni') {
					validarDni(input);
				} else {
					let value = input.value.trim();
					if (value === '') {
						mostrarError(input, `El ${campo} es obligatorio`);
					} else {
						ocultarError(input);
					}
				}

			}

			// Función para mostrar el mensaje de error en el input
			function mostrarError(input, mensaje) {
				input.style.borderColor = 'red';  // Cambiar el borde del input a rojo
				let errorMessage = input.nextElementSibling;
				if (!errorMessage || !errorMessage.classList.contains('error')) {
					errorMessage = document.createElement('div');
					errorMessage.className = 'error';
					input.parentNode.appendChild(errorMessage);
				}

				errorMessage.textContent = mensaje;
			}

			// Función para ocultar el mensaje de error
			function ocultarError(input) {
				input.style.borderColor = '';  // Restaurar el borde del input
				let errorMessage = input.nextElementSibling;
				if (errorMessage && errorMessage.classList.contains('error')) {
					errorMessage.textContent = '';
				}
			}

			fetchUserData();
		});

	</script>

<?php $this->stop() ?>
