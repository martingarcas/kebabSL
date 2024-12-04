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
		<!-- Contenedor principal de los kebabs -->
		<div class="form-container" id="kebabs-container">
			<h2>KEBABS</h2>
			<div class="tarjetas-container"></div>
		</div>

		<!-- Contenedor del formulario para agregar kebabs -->
		<div class="form-container" id="formulario-container" style="display: none;">
			<div id="formulario-kebab" class="formulario-kebab">
				<form action="/apiKebab" method="POST" id="form-agregar-kebab" enctype="multipart/form-data">
					<h2>Agregar Kebab</h2>
					<div class="spinner" id="spinner-add"></div>

					<fieldset class="header-kebab">
						<!-- Foto -->
						<!--						<label for="foto">Foto del Kebab:</label>-->
						<div class="inputs-kebab">

							<div class="foto-container" id="foto-container">
								<input type="file" id="foto" name="foto" accept="image/*">
								<img src="/img/iconos/agregar.png" alt="Agregar imagen" class="icono-agregar">
								<div id="foto-preview"></div>
							</div>
							<button type="button" id="camera-button" title="Tomar una foto">Tomar foto</button>
							<video id="player" autoplay style="display:none;"></video>
							<button type="button" id="capture-button" style="display:none;">Capturar</button>

						</div>

						<div class="inputs-kebab">

							<!-- Nombre -->
							<label for="nombre">Nombre:</label>
							<input type="text" id="nombre" name="nombre">

							<!-- Precio -->
							<!-- Indicador de Precio Base -->
							<div id="precio-base-container">
								<label>Precio Base (pan incluido): </label>
								<span id="precio-base">2€</span>
							</div>

							<!-- Indicador de Precio Total Dinámico -->
							<div id="precio-total-container">
								<label>Precio Estimado Total: </label>
								<span id="precio-total-crear">2.00€</span>
							</div>
							<label for="precio">Precio €</label>
							<input type="text" id="precio" value="2" name="precio" placeholder="€">

						</div>
					</fieldset>

					<!-- Ingredientes (Checkboxs) -->
					<fieldset class="fieldset-ingredientes">
						<legend>Ingredientes:</legend>
						<div id="ingredientes-container">
							<!-- Aquí se llenarán los checkboxes de ingredientes dinámicamente -->
						</div>
					</fieldset>

					<button id="guardar-kebab" class="btn" type="submit" disabled>Guardar Kebab</button>
					<button type="button" id="cancelar-formulario">Cancelar</button>
				</form>
			</div>
		</div>

		<!-- Contenedor del formulario para editar Kebabs -->
		<div class="form-container" id="formulario-container-edit" style="display: none;">
			<div id="formulario-kebab-edit" class="formulario-kebab-edit">
				<form action="/apiKebab" method="POST" id="form-edit-kebab" enctype="multipart/form-data">
					<h2>Editar Kebab</h2>
					<div class="spinner" id="spinner-edit"></div>

					<fieldset class="header-kebab">
						<!-- Foto -->
						<!--						<label for="foto">Foto del Kebab:</label>-->
						<div class="foto-container" id="foto-container-edit">
							<input type="file" id="foto" name="foto" accept="image/*">
							<img src="/img/iconos/agregar.png" alt="Agregar imagen" class="icono-agregar">
							<div id="foto-preview"></div>
						</div>

						<div class="inputs-kebab">

							<!-- Nombre -->
							<label for="nombre">Nombre:</label>
							<input type="text" id="nombre" name="nombre" required>

							<!-- Precio -->
							<!-- Indicador de Precio Base -->
							<div id="precio-base-container">
								<label>Precio Base (pan incluido): </label>
								<span id="precio-base">2€</span>
							</div>

							<!-- Indicador de Precio Total Dinámico -->
							<div id="precio-total-container">
								<label>Precio Estimado Total: </label>
								<span id="precio-total-edit">2.00€</span>
							</div>

							<label for="precio">Precio €</label>
							<input type="text" id="precio" name="precio" placeholder="€" required>

						</div>
					</fieldset>

					<!-- Ingredientes (Checkboxs) -->
					<fieldset class="fieldset-ingredientes">
						<legend>Ingredientes:</legend>
						<div id="ingredientes-container-edit">
							<!-- Aquí se llenarán los checkboxes de ingredientes dinámicamente -->
						</div>
					</fieldset>

					<button id="edit-kebab" class="btn" type="submit" disabled>Guardar Kebab</button>
					<button id="delete-kebab" class="btn-delete" type="submit">Borrar Kebab</button>
					<button type="button" id="cancelar-formulario">Cancelar</button>
				</form>
			</div>
		</div>

	</div>

<?php $this->stop() ?>

<?php $this->start('scripts'); ?>

	<script>

		document.addEventListener('DOMContentLoaded', () => {
			// Variables comunes
			const tarjetasContainer 		= document.querySelector('.tarjetas-container');
			const formularioContainer 		= document.querySelector('#formulario-container');
			const formularioContainerEdit 	= document.querySelector('#formulario-container-edit');
			const ingredientesContainerEdit = document.querySelector('#ingredientes-container-edit');
			const kebabsContainer 			= document.querySelector('#kebabs-container');
			const formAgregarKebab 			= document.querySelector('.card-agregar');
			const ingredientesContainer 	= document.querySelector('#ingredientes-container');
			const spinnerAdd 				= document.querySelector('#spinner-add');
			const spinnerEdit 				= document.querySelector('#spinner-edit');
			const PRECIO_BASE = 2.0; // Precio base fijo por el pan

			// Función para mostrar el spinner
			function showSpinner(spinner) {
				spinner.style.display = 'block';
			}

			// Función para ocultar el spinner
			function hideSpinner(spinner) {
				spinner.style.display = 'none';
			}

			// Llamar a la función para obtener los kebabs e ingredientes
			obtenerKebabsEIngredientes();

			/* ------------------------------------------------- */
			/*  SECCIÓN 1: MOSTRAR KEBABS Y BOTÓN "AGREGAR" */
			/* ------------------------------------------------- */

			async function obtenerKebabsEIngredientes() {

				const formData = new FormData();
				formData.append('action', 'show');  // Acción para obtener los kebabs

				// Fetch para obtener kebabs e ingredientes
				const response = await fetch('/apiKebab', { method: 'POST', body: formData });
				const data = await response.json();

				if (data.success) {
					const kebabs 		= data.kebabs;
					const ingredientes 	= data.ingredientes;

					// Crear y mostrar el botón "Agregar Kebab"
					const cardAgregar = crearCardAgregar();
					tarjetasContainer.appendChild(cardAgregar);
					let contador = -1;
					// Crear las tarjetas de kebabs y mostrarlas
					kebabs.reverse().forEach(kebab => {
						const card = crearCardIngrediente(kebab, ingredientes);
						card.kebab = ++contador;
						tarjetasContainer.appendChild(card);
					});

					// Crear los checkboxes para los ingredientes
					agregarIngredientesCheckBox(ingredientes);

					/* ------------------------------------------------- */
					/*  SECCIÓN 3: EDICIÓN DE UN KEBAB" */
					/* ------------------------------------------------- */
					tarjetasContainer.addEventListener('click', function (e) {
						let card = e.target.closest('#kebab-card');
						let index = card.kebab;
						let kebabEdit = kebabs[index];
						mostrarEditForm(kebabEdit);
						saveOriginalValues();

					})

					function mostrarEditForm(kebab) {

						formularioContainerEdit.id = kebab.id;

						// console.log(kebab.ingredientes)

						kebabsContainer.style.display 	= 'none';
						formularioContainerEdit.style.display 	= 'block';

						const fotoInput 	= formularioContainerEdit.querySelector('#foto');
						const fotoContainer = formularioContainerEdit.querySelector('#foto-container-edit');
						const fotoPreview 	= formularioContainerEdit.querySelector('#foto-preview');
						const nombreInput 	= formularioContainerEdit.querySelector('#nombre');
						const precioInput 	= formularioContainerEdit.querySelector('#precio');

						// Añadir los eventos change para cada campo de entrada
						fotoPreview.innerHTML = `<img src="${kebab.foto}" alt="Imagen seleccionada" class="foto-preview-img">`;
						fotoInput.addEventListener('change', (event) => {
							const file = event.target.files[0];
							if (file) {
								const reader = new FileReader();
								reader.onload = function(e) {
									fotoPreview.innerHTML = `<img src="${e.target.result}" alt="Imagen seleccionada" class="foto-preview-img">`;
								};
								reader.readAsDataURL(file);
							}
						});
						// Abrir el selector de imagen al hacer clic en el contenedor de foto
						fotoContainer.addEventListener('click', () => {
							fotoInput.click();
						});

						nombreInput.value = kebab.nombre;
						precioInput.value = kebab.precio;

						// Crear los checkboxes para los ingredientes
						agregarIngredientesEdit(ingredientes, kebab.ingredientes);

						// Función para cancelar el formulario de agregar kebab
						formularioContainerEdit.querySelector('#cancelar-formulario').addEventListener('click', () => {
							formularioContainerEdit.style.display = 'none';
							kebabsContainer.style.display = 'block';
						});

					}

					// Función para agregar los checkboxes de ingredientes
					function agregarIngredientesEdit(ingredientes, ingredientesKebab) {
						const ingredientesSet = new Set(Object.keys(ingredientesKebab).map(key => String(key))); // IDs como cadenas
						ingredientesContainerEdit.innerHTML = '';  // Limpiar el contenedor de ingredientes

						ingredientes.forEach(ingrediente => {
							const div = document.createElement('div');
							div.classList.add('ingrediente-checkbox');

							const checkbox = document.createElement('input');
							checkbox.type = 'checkbox';
							checkbox.id = `ingrediente-${ingrediente.id}`;
							checkbox.name = 'ingredientes[]';
							checkbox.value = ingrediente.id;
							checkbox.precio = ingrediente.precio; // Asignar el precio como atributo de datos
							checkbox.addEventListener('change', actualizarPrecioTotalEdit); // Llamar a la función de actualización

							// Verificar si el ingrediente ya está asociado al kebab
							if (ingredientesSet.has(String(ingrediente.id))) {
								checkbox.checked = true; // Marcar como seleccionado si aplica
							}

							const label = document.createElement('label');
							label.setAttribute('for', `ingrediente-${ingrediente.id}`);
							label.textContent = ingrediente.nombre;

							div.appendChild(checkbox);
							div.appendChild(label);
							ingredientesContainerEdit.appendChild(div);
						});

						// Calcular el precio total al cargar los ingredientes
						actualizarPrecioTotalEdit();  // Llamar la función de actualización para mostrar el precio
					}

				}

			}

			const formEditarKebab = document.querySelector('#form-edit-kebab');
			const btnUpdate = document.querySelector('#edit-kebab');
			const btnDelete = document.querySelector('#delete-kebab');
			formEditarKebab.addEventListener('change', (event) => {
				// Verificar si algún campo ha sido modificado
				// checkFormChanges();
				if (checkFormChanges()) {
					validarCampos(formEditarKebab, btnUpdate);
				}
			});

			// Crear un objeto para almacenar los valores originales de cada campo
			const originalValues = {};

			// Función para guardar los valores iniciales
			function saveOriginalValues() {
				const fields = document.querySelectorAll('#form-edit-kebab input, #form-edit-kebab select');

				fields.forEach(field => {
					if (field.type === 'checkbox') {
						// Para checkboxes de ingredientes, almacenamos un array de los seleccionados
						if (field.name === 'ingredientes[]') {
							if (!originalValues['ingredientes[]']) {
								originalValues['ingredientes[]'] = []; // Inicializamos como un array vacío
							}
							if (field.checked) {
								originalValues['ingredientes[]'].push(field.value); // Guardamos el valor si está seleccionado
							}
						} else {
							// Otros checkboxes no relacionados con ingredientes
							originalValues[field.name] = field.checked;
						}
					} else {
						// Para otros campos (input, select, etc.), almacenamos el valor
						originalValues[field.name] = field.value || ''; // Si no hay valor, guardamos cadena vacía
					}
				});
			}

			// Función para detectar cambios en los campos
			function checkFormChanges() {
				let hasChanges = false;

				const fields = document.querySelectorAll('#form-edit-kebab input, #form-edit-kebab select');

				fields.forEach(field => {
					let currentValue;

					if (field.type === 'checkbox') {
						if (field.name === 'ingredientes[]') {
							// Obtenemos los valores actuales de los checkboxes seleccionados
							const currentChecked = Array.from(document.querySelectorAll('input[name="ingredientes[]"]:checked'))
								.map(checkbox => checkbox.value);

							// Comparamos los arrays (ingredientes originales vs actuales)
							const originalChecked = originalValues['ingredientes[]'] || [];
							if (!arraysAreEqual(currentChecked, originalChecked)) {
								hasChanges = true;
							}
						} else {
							// Para otros checkboxes, comparamos el estado checked
							currentValue = field.checked;
							if (currentValue !== originalValues[field.name]) {
								hasChanges = true;
							}
						}
					} else {
						// Para otros campos (input, select, etc.), comparamos valores
						currentValue = field.value || '';
						if (currentValue !== originalValues[field.name]) {
							hasChanges = true;
						}
					}
				});

				// Activar o desactivar el botón de actualizar
				btnUpdate.disabled = !hasChanges;
				if (hasChanges) {
					return true;
				} else {
					return false;
				}
			}

			// Función para comparar arrays (ingredientes originales vs actuales)
			function arraysAreEqual(arr1, arr2) {
				if (arr1.length !== arr2.length) return false;
				return arr1.every(value => arr2.includes(value));
			}

			formEditarKebab.addEventListener('submit', async (event) => {

				let valid;
				event.preventDefault();  // Evitar comportamiento por defecto del formulario
				showSpinner(spinnerEdit);

				// Verificar si los campos "nombre" y "precio" están vacíos
				const nombre = formEditarKebab.querySelector('input[name="nombre"]');
				const precio = formEditarKebab.querySelector('input[name="precio"]');

				if (!nombre.value.trim()) {
					mostrarError(nombre, 'El nombre es obligatorio.');
					hideSpinner(spinnerEdit);
					// Habilitar o deshabilitar el botón según la validación
					valid = false;
					// return;  // Si el nombre está vacío, no se envía el formulario
				} else {
					valid = true;
				}

				// Validar el campo "precio"
				if (!precio.value.trim()) {
					mostrarError(precio, 'El precio es obligatorio.');
					hideSpinner(spinnerEdit);
					valid = false;
				} else if (isNaN(precio.value.trim())) {
					hideSpinner(spinnerEdit);
					mostrarError(precio, 'El precio debe ser un número.');
					valid = false;
				} else {
					valid = true;
				}

				// Si algún campo es inválido, no se envía el formulario
				if (!valid) {
					guardarBoton.disabled = true;
					return;
				}

				// Verificar si algún campo no es válido
				if (!formEditarKebab.checkValidity()) {
					return;  // Si algún campo es inválido, no se envía el formulario
				}

				// Obtener el botón que fue presionado
				const botonPresionado = event.submitter;

				const formData = new FormData(formEditarKebab);
				formData.append('id', formularioContainerEdit.id);  // Aseguramos que siempre se pase el ID del kebab
				// Añadir los ingredientes seleccionados al FormData si es necesario
				const selectedIngredientes = [];
				document.querySelectorAll('input[name="ingredientes[]"]:checked').forEach(checkbox => {
					selectedIngredientes.push(checkbox.value);
				});
				formData.append('ingredientes', JSON.stringify(selectedIngredientes));

				// Verificar si se ha seleccionado una nueva imagen
				const imageInput 	= document.querySelector('input[name="foto"]');  // O el selector de tu campo de imagen
				const existingImage = document.querySelector('.foto-preview-img');  // Asegúrate de que este ID sea el correcto

				if (!imageInput.files.length && existingImage) {
					// Si no se ha seleccionado una nueva imagen, enviar la imagen anterior
					formData.append('foto', existingImage.src);
				}

				try {
					if (botonPresionado.id === btnDelete.id) {
						// Si el botón presionado es el de eliminar
						formData.append('action', 'delete');

						const response = await fetch('/apiKebab', {
							method: 'POST',
							body: formData
						});

						const data = await response.json();

						if (data.success) {
							// console.log(data);
							// Guardar mensaje de éxito en sessionStorage
							sessionStorage.setItem('flash_message', JSON.stringify({
								message: data.message || 'Kebab eliminado correctamente',
								type: 'success'
							}));

							await obtenerKebabsEIngredientes();  // Recargar los kebabs después de eliminar uno
							window.location.href = data.redirect_url || '/kebabs';  // Redirigir a la página de kebabs
						} else {
							// Guardar mensaje de error en sessionStorage
							sessionStorage.setItem('flash_message', JSON.stringify({
								message: data.message || 'Hubo un error al eliminar el kebab',
								type: 'error'
							}));

							window.location.href = '/kebabs';  // Redirigir a la página de kebabs
						}

					} else if (botonPresionado.id === btnUpdate.id) {
						// Si el botón presionado es el de actualizar
						formData.append('action', 'update');

						const response = await fetch('/apiKebab', {
							method: 'POST',
							body: formData
						});

						const data = await response.json();

						if (data.success) {
							// console.log(data);
							// Guardar mensaje de éxito en sessionStorage
							sessionStorage.setItem('flash_message', JSON.stringify({
								message: data.message || 'Kebab actualizado correctamente',
								type: 'success'
							}));

							await obtenerKebabsEIngredientes();  // Recargar los ingredientes después de actualizar uno
							window.location.href = data.redirect_url || '/kebabs';  // Redirigir a la página de kebabs
						} else {
							// Guardar mensaje de error en sessionStorage
							sessionStorage.setItem('flash_message', JSON.stringify({
								message: data.message || 'Hubo un error al actualizar el kebabs',
								type: 'error'
							}));

							window.location.href = '/kebabs';  // Redirigir a la página de kebabs
						}
					}

				} catch (error) {
					console.error('Error al enviar la solicitud:', error);
					// Guardar mensaje de error en sessionStorage en caso de fallo
					sessionStorage.setItem('flash_message', JSON.stringify({
						message: 'Hubo un problema al procesar el kebab. Intenta nuevamente.',
						type: 'error'
					}));

					window.location.href = '/kebabs';  // Redirigir a la página de kebabs
				}
			});

			/* -------------------------------------- */
			/*  SECCIÓN 2: CREACIÓN DE KEBAB */
			/* -------------------------------------- */
			// Función para crear el card del botón "Agregar Kebab"
			function crearCardAgregar() {
				const card = document.createElement('div');
				card.classList.add('kebab-card', 'card-agregar');
				const iconoAgregar = document.createElement('img');
				iconoAgregar.classList.add('icono-agregar');
				iconoAgregar.src = '/img/iconos/agregar.png';
				iconoAgregar.alt = 'Agregar nuevo ingrediente';
				card.appendChild(iconoAgregar);
				const texto = document.createElement('h3');
				texto.textContent = 'Agregar Kebab';
				card.appendChild(texto);

				// Mostrar formulario al hacer clic
				card.addEventListener('click', () => {
					kebabsContainer.style.display = 'none';
					formularioContainer.style.display = 'block';
					formAgregarKebab.style.display = 'none';
				});

				return card;
			}

			// Función para crear el card de cada kebab
			function crearCardIngrediente(kebab, ingredientes) {
				const card = document.createElement('div');
				card.classList.add('kebab-card');
				card.id = 'kebab-card';
				const img = document.createElement('img');
				img.classList.add('kebab-img');
				img.src = kebab.foto;
				img.alt = kebab.nombre;
				card.appendChild(img);

				const nombre = document.createElement('h3');
				nombre.textContent = kebab.nombre;
				card.appendChild(nombre);

				const precio = document.createElement('p');
				precio.classList.add('precio');
				precio.textContent = `${kebab.precio}€`;
				card.appendChild(precio);

				// Crear la sección de ingredientes
				const ingredientesContainer = document.createElement('div');
				ingredientesContainer.classList.add('ingredientes-container');

				if (typeof kebab.ingredientes === 'object' && kebab.ingredientes !== null) {
					for (const idIngrediente in kebab.ingredientes) {
						if (kebab.ingredientes.hasOwnProperty(idIngrediente)) {
							const ingrediente = ingredientes.find(a => a.id.toString() === idIngrediente);
							if (ingrediente) {
								const imgIngrediente = document.createElement('img');
								imgIngrediente.classList.add('ingrediente-img');
								imgIngrediente.src = ingrediente.foto;
								imgIngrediente.alt = ingrediente.nombre;
								const tooltip = document.createElement('span');
								tooltip.classList.add('tooltip');
								tooltip.textContent = ingrediente.nombre;
								ingredientesContainer.appendChild(imgIngrediente);
								ingredientesContainer.appendChild(tooltip);
							}
						}
					}
				} else {
					const noIngredientes = document.createElement('span');
					noIngredientes.classList.add('no-ingredientes');
					noIngredientes.textContent = 'Sin Ingredientes';
					ingredientesContainer.appendChild(noIngredientes);
				}

				card.appendChild(ingredientesContainer);

				return card;
			}

			// Función para agregar los checkboxes de ingredientes
			function agregarIngredientesCheckBox(ingredientes) {
				ingredientesContainer.innerHTML = '';  // Limpiar el contenedor de ingredientes
				ingredientes.forEach(ingrediente => {
					const div = document.createElement('div');
					div.classList.add('ingrediente-checkbox');
					const checkbox = document.createElement('input');
					checkbox.type = 'checkbox';
					checkbox.id = `ingrediente-${ingrediente.id}`;
					checkbox.name = 'ingredientes[]';
					checkbox.value = ingrediente.id;
					checkbox.precio = ingrediente.precio; // Asume que cada ingrediente tiene un precio en el objeto
					//Asocia la función de actualización a los eventos de los checkboxes de ingredientes
					checkbox.addEventListener('change', actualizarPrecioTotalCrear);  // Llamar a la función de actualización
					const label = document.createElement('label');
					label.setAttribute('for', `ingrediente-${ingrediente.id}`);
					label.textContent = ingrediente.nombre;
					div.appendChild(checkbox);
					div.appendChild(label);
					ingredientesContainer.appendChild(div);
				});
			}

			/* -------------------------------------- */
			/*  SECCIÓN 2: CREACIÓN DE KEBAB */
			/* -------------------------------------- */

			// Función para manejar la vista previa de la imagen al seleccionar un archivo
			//FORMULARIO DE AGREGAR INGREDIENTE
			const formAgregarKebabElement = document.querySelector('#form-agregar-kebab');
			const fotoInput = document.querySelector('#foto');
			const fotoContainer = document.querySelector('#foto-container');
			const fotoPreview = document.querySelector('#foto-preview');
			const cameraButton = document.querySelector('#camera-button');
			const captureButton = document.querySelector('#capture-button');
			const player = document.querySelector('#player');
			const nombreInput = document.querySelector('#nombre');
			const precioInput = document.querySelector('#precio');
			const guardarBoton = formAgregarKebabElement.querySelector('#guardar-kebab');

			// Variable para almacenar el blob de la foto capturada
			let capturedBlob = null;

			// Función para mostrar un mensaje de error debajo del campo
			function mostrarError(campo, mensajeError) {
				const newErrorElement = document.createElement('div');
				newErrorElement.classList.add('error-form');
				newErrorElement.id = campo.name + '-error-form';
				newErrorElement.textContent = mensajeError; //innerhtml
				campo.insertAdjacentElement('afterend', newErrorElement);
			}

			// Función para limpiar los errores de un campo
			function limpiarErrores(campo) {

				let errorElement = campo.name + '-error-form';
				let errorDelete = document.querySelector(`#${errorElement}`);

				if (errorDelete) {
					errorDelete.remove();
				}
			}

			// Añadir los eventos change para cada campo de entrada
			fotoInput.addEventListener('change', (event) => {
				const file = event.target.files[0];
				if (file) {
					const reader = new FileReader();
					reader.onload = function(e) {
						fotoPreview.innerHTML = `<img src="${e.target.result}" alt="Imagen seleccionada" class="foto-preview-img">`;
					};
					reader.readAsDataURL(file);
				}
			});

			// Abrir el selector de imagen al hacer clic en el contenedor de foto
			fotoContainer.addEventListener('click', () => {
				fotoInput.click();
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

			// Llamar a la función validarCampos cada vez que se cambie un campo
			nombreInput.addEventListener('change', () => validarCampos(formAgregarKebabElement, guardarBoton));
			precioInput.addEventListener('change', () => validarCampos(formAgregarKebabElement, guardarBoton));

			// Función para manejar el envío del formulario para agregar un ingrediente
			formAgregarKebabElement.addEventListener('submit', async (event) => {

				let valid;
				event.preventDefault();  // Evitar comportamiento por defecto del formulario
				showSpinner(spinnerAdd);

				// Verificar si los campos "nombre" y "precio" están vacíos
				const nombre = formAgregarKebabElement.querySelector('input[name="nombre"]');
				const precio = formAgregarKebabElement.querySelector('input[name="precio"]');

				if (!nombre.value.trim()) {
					mostrarError(nombre, 'El nombre es obligatorio.');
					hideSpinner(spinnerAdd);
					// Habilitar o deshabilitar el botón según la validación
					valid = false;
					// return;  // Si el nombre está vacío, no se envía el formulario
				} else {
					valid = true;
				}

				// Validar el campo "precio"
				if (!precio.value.trim()) {
					mostrarError(precio, 'El precio es obligatorio.');
					hideSpinner(spinnerAdd);
					valid = false;
				} else if (isNaN(precio.value.trim())) {
					hideSpinner(spinnerAdd);
					mostrarError(precio, 'El precio debe ser un número.');
					valid = false;
				} else {
					valid = true;
				}

				// Si algún campo es inválido, no se envía el formulario
				if (!valid) {
					guardarBoton.disabled = true;
					return;
				}

				// Verificar si algún campo no es válido
				if (!formAgregarKebabElement.checkValidity()) {
					return;  // Si algún campo es inválido, no se envía el formulario
				}

				const formData = new FormData(formAgregarKebabElement);
				formData.append('action', 'insert');

				// Añadir los ingredientes seleccionados al FormData
				const selectedIngredientes = [];
				document.querySelectorAll('input[name="ingredientes[]"]:checked').forEach(checkbox => {
					selectedIngredientes.push(checkbox.value);  // Añadir el ID del ingrediente
				});
				formData.append('ingredientes', JSON.stringify(selectedIngredientes));

				// Verificar si existe una imagen capturada desde la cámara
				if (capturedBlob) {
					formData.append('foto', capturedBlob, `foto-capturada-${Date.now()}.jpg`);
				}

				try {
					const response = await fetch('/apiKebab', {
						method: 'POST',
						body: formData
					});

					const data = await response.json();

					if (data.success) {
						// Guardar mensaje de éxito en sessionStorage
						sessionStorage.setItem('flash_message', JSON.stringify({
							message: data.message || 'Kebab agregado correctamente',
							type: 'success'
						}));
						formAgregarKebabElement.reset();
						await obtenerKebabsEIngredientes();  // Recargar los ingredientes después de agregar uno nuevo
						window.location.href = data.redirect_url || '/kebabs';  // Redirigir a la página de ingredientes
					} else {
						// Guardar mensaje de error en sessionStorage
						sessionStorage.setItem('flash_message', JSON.stringify({
							message: data.message || 'Hubo un error al agregar el kebab',
							type: 'error'
						}));
						window.location.href = '/kebabs';  // Redirigir a la página de ingredientes
					}
				} catch (error) {
					console.error('Error al enviar la solicitud:', error);
					// Guardar mensaje de error en sessionStorage en caso de fallo
					sessionStorage.setItem('flash_message', JSON.stringify({
						message: 'Hubo un problema al agregar el kebab. Intenta nuevamente.',
						type: 'error'
					}));
					window.location.href = '/kebabs';  // Redirigir a la página de ingredientes
				}
			});

			// Función para cancelar el formulario de agregar ingrediente
			document.querySelector('#cancelar-formulario').addEventListener('click', () => {
				formularioContainer.style.display = 'none';
				ingredientesContainer.style.display = 'block';
				kebabsContainer.style.display = 'block';
			});

			// FUNCIONES DE VALIDACIÓN
			// Función generalizada para validar cualquier campo
			function validarCampo(campo, regex = null, mensajeError = '') {
				const valor = campo.value.trim();

				if (valor === '') {
					limpiarErrores(campo);
					mostrarError(campo, `${campo.name} es obligatorio`);
					return false;
				} else if (regex && !regex.test(valor)) {
					limpiarErrores(campo);
					mostrarError(campo, mensajeError);
					return false;
				} else {
					limpiarErrores(campo);
					return true;
				}
			}

			// Función para validar el nombre
			function validarNombre(campo) {
				return validarCampo(campo, null, 'El nombre es obligatorio');
			}

			// Función para validar el precio
			function validarPrecio(campo) {
				const regexPrecio = /^[0-9]+(\.[0-9]{1,2})?$/;
				const valor = parseFloat(campo.value.trim());

				if (valor < PRECIO_BASE) {
					limpiarErrores(campo);
					mostrarError(campo, `El precio no puede ser menor a ${PRECIO_BASE}€`);
					return false;
				}

				return validarCampo(campo, regexPrecio, 'El precio debe ser un valor numérico válido');
			}

			// Función para validar todos los campos
			function validarCampos(formulario, boton) {
				let esValido = true;

				// Validar nombre y precio
				const nombreValido = validarNombre(formulario.querySelector('#nombre'));
				const precioValido = validarPrecio(formulario.querySelector('#precio'));

				// Verificar si ambos campos son válidos
				if (!nombreValido || !precioValido) {
					esValido = false;
				}

				// Habilitar o deshabilitar el botón según la validación
				boton.disabled = !esValido;

				return esValido;
			}

			function actualizarPrecioTotalCrear() {
				let precioTotal = PRECIO_BASE;  // Inicia con el precio base (2€)

				// Calcular el precio total sumando los ingredientes seleccionados en el contenedor correspondiente
				ingredientesContainer.querySelectorAll('input[name="ingredientes[]"]:checked').forEach(checkbox => {
					const precioIngrediente = parseFloat(checkbox.precio || 0);  // Obtener el precio desde el atributo 'data'
					precioTotal += precioIngrediente;  // Sumar al precio total
				});

				// Actualizar el texto del precio total en el DOM
				const precioTotalElementoCrear = document.querySelector('#precio-total-crear');  // Asegurarse de que el precio total se actualiza
				if (precioTotalElementoCrear) {
					precioTotalElementoCrear.textContent = `${precioTotal.toFixed(2)}€`;
				}
			}

			function actualizarPrecioTotalEdit() {
				let precioTotal = PRECIO_BASE;  // Inicia con el precio base (2€)

				// Calcular el precio total sumando los ingredientes seleccionados en el contenedor correspondiente
				ingredientesContainerEdit.querySelectorAll('input[name="ingredientes[]"]:checked').forEach(checkbox => {
					const precioIngrediente = parseFloat(checkbox.precio || 0);  // Obtener el precio desde el atributo 'data'
					precioTotal += precioIngrediente;  // Sumar al precio total
				});

				// Actualizar el texto del precio total en el DOM
				const precioTotalElementoEdit = document.querySelector('#precio-total-edit');  // Asegurarse de que el precio total se actualiza
				if (precioTotalElementoEdit) {
					precioTotalElementoEdit.textContent = `${precioTotal.toFixed(2)}€`;
				}
			}


		});

	</script>

<?php $this->stop(); ?>