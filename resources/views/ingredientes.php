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
		<!-- Contenedor principal de los ingredientes -->
		<div class="form-container" id="ingredientes-container">
			<h2>INGREDIENTES</h2>
			<!-- Botón para generar el PDF -->
			<button id="generar-pdf" class="btn-pdf">Descargar listado de Alérgenos</button>
			<div class="spinner" id="spinner"></div>
			<div class="tarjetas-container"></div>
		</div>

		<!-- Contenedor del formulario para agregar ingredientes -->
		<div class="form-container" id="formulario-container" style="display: none;">
			<div id="formulario-ingrediente" class="formulario-ingrediente">
				<form action="/apiIngrediente" method="POST" id="form-agregar-ingrediente" enctype="multipart/form-data">
					<h2>Agregar Ingrediente</h2>

					<fieldset class="header-ingredient">
						<!-- Foto -->
						<!--						<label for="foto">Foto del Ingrediente:</label>-->
						<div class="foto-container" id="foto-container">
							<input type="file" id="foto" name="foto" accept="image/*">
							<img src="/img/iconos/agregar.png" alt="Agregar imagen" class="icono-agregar">
							<div id="foto-preview"></div>
						</div>

						<div class="inputs-ingredient">

							<!-- Nombre -->
							<label for="nombre">Nombre:</label>
							<input type="text" id="nombre" name="nombre" required>

							<!-- Precio -->
							<label for="precio">Precio €</label>
							<input type="text" id="precio" name="precio" placeholder="€" required>

						</div>
					</fieldset>

					<!-- Alergenos (Checkboxs) -->
					<fieldset class="fieldset-alergenos">
						<legend>Alergenos:</legend>
						<div id="alergenos-container">
							<!-- Aquí se llenarán los checkboxes de alérgenos dinámicamente -->
						</div>
					</fieldset>

					<button id="guardar-ingrediente" class="btn" type="submit" disabled>Guardar Ingrediente</button>
					<button type="button" id="cancelar-formulario">Cancelar</button>
				</form>
			</div>
		</div>

		<!-- Contenedor del formulario para editar ingredientes -->
		<div class="form-container" id="formulario-container-edit" style="display: none;">
			<div id="formulario-ingrediente-edit" class="formulario-ingrediente-edit">
				<form action="/apiIngrediente" method="POST" id="form-edit-ingrediente" enctype="multipart/form-data">
					<h2>Editar Ingrediente</h2>

					<fieldset class="header-ingredient">
						<!-- Foto -->
						<!--						<label for="foto">Foto del Ingrediente:</label>-->
						<div class="foto-container" id="foto-container-edit">
							<input type="file" id="foto" name="foto" accept="image/*">
							<img src="/img/iconos/agregar.png" alt="Agregar imagen" class="icono-agregar">
							<div id="foto-preview"></div>
						</div>

						<div class="inputs-ingredient">

							<!-- Nombre -->
							<label for="nombre">Nombre:</label>
							<input type="text" id="nombre" name="nombre" required>

							<!-- Precio -->
							<label for="precio">Precio €</label>
							<input type="text" id="precio" name="precio" placeholder="€" required>

						</div>
					</fieldset>

					<!-- Alergenos (Checkboxs) -->
					<fieldset class="fieldset-alergenos">
						<legend>Alergenos:</legend>
						<div id="alergenos-container-edit">
							<!-- Aquí se llenarán los checkboxes de alérgenos dinámicamente -->
						</div>
					</fieldset>

					<button id="edit-ingrediente" class="btn" type="submit" disabled>Guardar Ingrediente</button>
					<button id="delete-ingrediente" class="btn-delete" type="submit">Borrar Ingrediente</button>
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
			const alergenosContainerEdit 	= document.querySelector('#alergenos-container-edit');
			const ingredientesContainer 	= document.querySelector('#ingredientes-container');
			const formAgregarIngrediente 	= document.querySelector('.card-agregar');
			const alergenosContainer 		= document.querySelector('#alergenos-container');
			const spinner 					= document.getElementById('spinner');

			// Función para mostrar el spinner
			function showSpinner() {
				spinner.style.display = 'block';
			}

			// Función para ocultar el spinner
			function hideSpinner() {
				spinner.style.display = 'none';
			}

			// Llamar a la función para obtener los ingredientes y alérgenos
			obtenerIngredientesYAlergenos();

			/* ------------------------------------------------- */
			/*  SECCIÓN 1: MOSTRAR INGREDIENTES Y BOTÓN "AGREGAR" */
			/* ------------------------------------------------- */

			async function obtenerIngredientesYAlergenos() {

				const formData = new FormData();
				formData.append('action', 'show');  // Acción para obtener los ingredientes

				// Fetch para obtener ingredientes y alérgenos
				const response = await fetch('/apiIngrediente', { method: 'POST', body: formData });
				const data = await response.json();

				if (data.success) {
					const ingredientes = data.ingredientes;
					const alergenos = data.alergenos;

					// Crear y mostrar el botón "Agregar Ingrediente"
					const cardAgregar = crearCardAgregar();
					tarjetasContainer.appendChild(cardAgregar);
					let contador = -1;
					// Crear las tarjetas de ingredientes y mostrarlas
					ingredientes.reverse().forEach(ingrediente => {
						const card = crearCardIngrediente(ingrediente, alergenos);
						card.ingrediente = ++contador;
						tarjetasContainer.appendChild(card);
					});

					// Crear los checkboxes para los alérgenos
					agregarAlergenosCheckBox(alergenos);

					/* ------------------------------------------------- */
					/*  SECCIÓN 3: EDICIÓN DE UN INGREDIENTE" */
					/* ------------------------------------------------- */
					tarjetasContainer.addEventListener('click', function (e) {
						let card = e.target.closest('#ingrediente-card');
						let index = card.ingrediente;
						let ingredienteEdit = ingredientes[index];
						mostrarEditForm(ingredienteEdit);
						saveOriginalValues();

					})

					function mostrarEditForm(ingrediente) {

						formularioContainerEdit.id = ingrediente.id;

						// console.log(ingrediente.alergenos)

						ingredientesContainer.style.display 	= 'none';
						formularioContainerEdit.style.display 	= 'block';

						const fotoInput 	= formularioContainerEdit.querySelector('#foto');
						const fotoContainer = formularioContainerEdit.querySelector('#foto-container-edit');
						const fotoPreview 	= formularioContainerEdit.querySelector('#foto-preview');
						const nombreInput 	= formularioContainerEdit.querySelector('#nombre');
						const precioInput 	= formularioContainerEdit.querySelector('#precio');

						// Añadir los eventos change para cada campo de entrada
						fotoPreview.innerHTML = `<img src="${ingrediente.foto}" alt="Imagen seleccionada" class="foto-preview-img">`;
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

						nombreInput.value = ingrediente.nombre;
						precioInput.value = ingrediente.precio;

						// Crear los checkboxes para los alérgenos
						agregarAlergenosEdit(alergenos, ingrediente.alergenos);

						// Función para cancelar el formulario de agregar ingrediente
						formularioContainerEdit.querySelector('#cancelar-formulario').addEventListener('click', () => {
							formularioContainerEdit.style.display = 'none';
							ingredientesContainer.style.display = 'block';
						});

					}

					// Función para agregar los checkboxes de alérgenos
					function agregarAlergenosEdit(alergenos, alergenosIngrediente) {
						// Crear un Set con los ids de los alérgenos asociados al ingrediente
						const alergenosSet = new Set(Object.keys(alergenosIngrediente));
						alergenosContainerEdit.innerHTML = '';  // Limpiar el contenedor de alérgenos
						alergenos.forEach(alergeno => {
							const div = document.createElement('div');
							div.classList.add('alergeno-checkbox');
							const checkbox = document.createElement('input');
							checkbox.type = 'checkbox';
							checkbox.id = `alergeno-${alergeno.id}`;
							checkbox.name = 'alergenos[]';
							checkbox.value = alergeno.id;
							// Verificar si el ID del alérgeno está en el Set
							if (alergenosSet.has(alergeno.id)) {
								checkbox.checked = true;  // Si está en el Set, marcar el checkbox como seleccionado
							}

							// for (let key in alergenosIngrediente) {
							// 	if (alergeno.id === key)
							// 	checkbox.checked = true;
							// }
							// console.log(checkbox.value)
							const label = document.createElement('label');
							label.setAttribute('for', `alergeno-${alergeno.id}`);
							label.textContent = alergeno.nombre;
							div.appendChild(checkbox);
							div.appendChild(label);
							alergenosContainerEdit.appendChild(div);
						});
					}


					/* -------------------------------------- */
					/*  SECCIÓN PDF: GENERAR Y DESCARGAR PDF */
					/* -------------------------------------- */
					const generarPdfButton = document.querySelector('#generar-pdf');
					generarPdfButton.addEventListener('click', async () => {

						showSpinner();
						// Crear un objeto FormData para enviar la solicitud
						const formData = new FormData();

						// Agregar la acción 'pdf' al FormData
						formData.append('action', 'pdf');

						// Agregar los alérgenos seleccionados al FormData (esto debe estar en formato de array)
						// Array de objetos con {id, nombre, foto}
						alergenos.forEach(alergeno => {
							formData.append('alergenos[]', JSON.stringify(alergeno));
						});

						try {
							const responsePdf = await fetch('/apiIngrediente', {
								method: 'POST',
								body: formData // Enviar los datos con FormData
							});

							// Verificar si la respuesta es correcta
							if (responsePdf.ok) {
								const blob = await responsePdf.blob();
								const url = window.URL.createObjectURL(blob);
								const a = document.createElement('a');
								a.href = url;
								a.download = 'alergenos.pdf'; // Nombre del archivo PDF
								document.body.appendChild(a);
								a.click();
								a.remove();
							} else {
								alert('Hubo un error al generar el PDF');
							}
						} catch (error) {
							console.error('Error al generar el PDF:', error);
							alert('Hubo un error al generar el PDF');
						} finally {
							hideSpinner();
						}
					});

				}

			}

			const formEditarIngrediente = document.querySelector('#form-edit-ingrediente');
			formEditarIngrediente.addEventListener('change', (event) => {
				// Verificar si algún campo ha sido modificado
				checkFormChanges();
			});

			// Crear un objeto para almacenar los valores originales de cada campo
			const originalValues = {};

			// Función para guardar los valores iniciales
			function saveOriginalValues() {
				const fields = document.querySelectorAll('#form-edit-ingrediente input, #form-edit-ingrediente select');

				fields.forEach(field => {
					if (field.type === 'checkbox') {
						// Para checkboxes de alérgenos, almacenamos un array de los seleccionados
						if (field.name === 'alergenos[]') {
							if (!originalValues['alergenos[]']) {
								originalValues['alergenos[]'] = []; // Inicializamos como un array vacío
							}
							if (field.checked) {
								originalValues['alergenos[]'].push(field.value); // Guardamos el valor si está seleccionado
							}
						} else {
							// Otros checkboxes no relacionados con alérgenos
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

				const fields = document.querySelectorAll('#form-edit-ingrediente input, #form-edit-ingrediente select');

				fields.forEach(field => {
					let currentValue;

					if (field.type === 'checkbox') {
						if (field.name === 'alergenos[]') {
							// Obtenemos los valores actuales de los checkboxes seleccionados
							const currentChecked = Array.from(document.querySelectorAll('input[name="alergenos[]"]:checked'))
								.map(checkbox => checkbox.value);

							// Comparamos los arrays (alérgenos originales vs actuales)
							const originalChecked = originalValues['alergenos[]'] || [];
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
			}

			// Función para comparar arrays (alérgenos originales vs actuales)
			function arraysAreEqual(arr1, arr2) {
				if (arr1.length !== arr2.length) return false;
				return arr1.every(value => arr2.includes(value));
			}

			const btnUpdate = document.querySelector('#edit-ingrediente');
			const btnDelete = document.querySelector('#delete-ingrediente');
			formEditarIngrediente.addEventListener('submit', async (event) => {
				event.preventDefault();  // Evitar comportamiento por defecto del formulario

				// Obtener el botón que fue presionado
				const botonPresionado = event.submitter;

				// Validar campos si es necesario
				// validarNombre();
				// validarPrecio();

				// Verificar si algún campo no es válido
				// if (!formAgregarIngredienteElement.checkValidity()) {
				//   return;  // Si algún campo es inválido, no se envía el formulario
				// }

				const formData = new FormData(formEditarIngrediente);
				formData.append('id', formularioContainerEdit.id);  // Aseguramos que siempre se pase el ID del ingrediente
				// Añadir los alérgenos seleccionados al FormData si es necesario
				const selectedAlergenos = [];
				document.querySelectorAll('input[name="alergenos[]"]:checked').forEach(checkbox => {
					selectedAlergenos.push(checkbox.value);
				});
				formData.append('alergenos', JSON.stringify(selectedAlergenos));

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

						const response = await fetch('/apiIngrediente', {
							method: 'POST',
							body: formData
						});

						const data = await response.json();

						if (data.success) {
							// console.log(data);
							// Guardar mensaje de éxito en sessionStorage
							sessionStorage.setItem('flash_message', JSON.stringify({
								message: data.message || 'Ingrediente eliminado correctamente',
								type: 'success'
							}));

							await obtenerIngredientesYAlergenos();  // Recargar los ingredientes después de eliminar uno
							window.location.href = data.redirect_url || '/ingredientes';  // Redirigir a la página de ingredientes
						} else {
							// Guardar mensaje de error en sessionStorage
							sessionStorage.setItem('flash_message', JSON.stringify({
								message: data.message || 'Hubo un error al eliminar el ingrediente',
								type: 'error'
							}));

							window.location.href = '/ingredientes';  // Redirigir a la página de ingredientes
						}

					} else if (botonPresionado.id === btnUpdate.id) {
						// Si el botón presionado es el de actualizar
						formData.append('action', 'update');  // O la acción que corresponde al update

						const response = await fetch('/apiIngrediente', {
							method: 'POST',
							body: formData
						});

						const data = await response.json();

						if (data.success) {
							// console.log(data);
							// Guardar mensaje de éxito en sessionStorage
							sessionStorage.setItem('flash_message', JSON.stringify({
								message: data.message || 'Ingrediente actualizado correctamente',
								type: 'success'
							}));

							await obtenerIngredientesYAlergenos();  // Recargar los ingredientes después de actualizar uno
							window.location.href = data.redirect_url || '/ingredientes';  // Redirigir a la página de ingredientes
						} else {
							// Guardar mensaje de error en sessionStorage
							sessionStorage.setItem('flash_message', JSON.stringify({
								message: data.message || 'Hubo un error al actualizar el ingrediente',
								type: 'error'
							}));

							window.location.href = '/ingredientes';  // Redirigir a la página de ingredientes
						}
					}

				} catch (error) {
					console.error('Error al enviar la solicitud:', error);
					// Guardar mensaje de error en sessionStorage en caso de fallo
					sessionStorage.setItem('flash_message', JSON.stringify({
						message: 'Hubo un problema al procesar el ingrediente. Intenta nuevamente.',
						type: 'error'
					}));

					window.location.href = '/ingredientes';  // Redirigir a la página de ingredientes
				}
			});

			/* -------------------------------------- */
			/*  SECCIÓN 2: CREACIÓN DE INGREDIENTE */
			/* -------------------------------------- */
			// Función para crear el card del botón "Agregar Ingrediente"
			function crearCardAgregar() {
				const card = document.createElement('div');
				card.classList.add('ingrediente-card', 'card-agregar');
				const iconoAgregar = document.createElement('img');
				iconoAgregar.classList.add('icono-agregar');
				iconoAgregar.src = '/img/iconos/agregar.png';
				iconoAgregar.alt = 'Agregar nuevo ingrediente';
				card.appendChild(iconoAgregar);
				const texto = document.createElement('h3');
				texto.textContent = 'Agregar Ingrediente';
				card.appendChild(texto);

				// Mostrar formulario al hacer clic
				card.addEventListener('click', () => {
					ingredientesContainer.style.display = 'none';
					formularioContainer.style.display = 'block';
					formAgregarIngrediente.style.display = 'none';
				});

				return card;
			}

			// Función para crear el card de cada ingrediente
			function crearCardIngrediente(ingrediente, alergenos) {
				const card = document.createElement('div');
				card.classList.add('ingrediente-card');
				card.id = 'ingrediente-card';
				const img = document.createElement('img');
				img.classList.add('ingrediente-img');
				img.src = ingrediente.foto;
				img.alt = ingrediente.nombre;
				card.appendChild(img);

				const nombre = document.createElement('h3');
				nombre.textContent = ingrediente.nombre;
				card.appendChild(nombre);

				const precio = document.createElement('p');
				precio.classList.add('precio');
				precio.textContent = `${ingrediente.precio}€`;
				card.appendChild(precio);

				// Crear la sección de alérgenos
				const alergenosContainer = document.createElement('div');
				alergenosContainer.classList.add('alergenos-container');

				if (typeof ingrediente.alergenos === 'object' && ingrediente.alergenos !== null) {
					for (const idAlergeno in ingrediente.alergenos) {
						if (ingrediente.alergenos.hasOwnProperty(idAlergeno)) {
							const alergeno = alergenos.find(a => a.id.toString() === idAlergeno);
							if (alergeno) {
								const imgAlergeno = document.createElement('img');
								imgAlergeno.classList.add('alergeno-img');
								imgAlergeno.src = alergeno.foto;
								imgAlergeno.alt = alergeno.nombre;
								const tooltip = document.createElement('span');
								tooltip.classList.add('tooltip');
								tooltip.textContent = alergeno.nombre;
								alergenosContainer.appendChild(imgAlergeno);
								alergenosContainer.appendChild(tooltip);
							}
						}
					}
				} else {
					const noAlergenos = document.createElement('span');
					noAlergenos.classList.add('no-alergenos');
					noAlergenos.textContent = 'Sin alérgenos';
					alergenosContainer.appendChild(noAlergenos);
				}

				card.appendChild(alergenosContainer);

				return card;
			}

			// Función para agregar los checkboxes de alérgenos
			function agregarAlergenosCheckBox(alergenos) {
				alergenosContainer.innerHTML = '';  // Limpiar el contenedor de alérgenos
				alergenos.forEach(alergeno => {
					const div = document.createElement('div');
					div.classList.add('alergeno-checkbox');
					const checkbox = document.createElement('input');
					checkbox.type = 'checkbox';
					checkbox.id = `alergeno-${alergeno.id}`;
					checkbox.name = 'alergenos[]';
					checkbox.value = alergeno.id;
					const label = document.createElement('label');
					label.setAttribute('for', `alergeno-${alergeno.id}`);
					label.textContent = alergeno.nombre;
					div.appendChild(checkbox);
					div.appendChild(label);
					alergenosContainer.appendChild(div);
				});
			}

			/* -------------------------------------- */
			/*  SECCIÓN 2: CREACIÓN DE INGREDIENTE */
			/* -------------------------------------- */

			// Función para manejar la vista previa de la imagen al seleccionar un archivo
			const fotoInput = document.querySelector('#foto');
			const fotoContainer = document.querySelector('#foto-container');
			const fotoPreview = document.querySelector('#foto-preview');
			const nombreInput = document.querySelector('#nombre');
			const precioInput = document.querySelector('#precio');

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

			//FORMULARIO DE AGREGAR INGREDIENTE
			const formAgregarIngredienteElement = document.querySelector('#form-agregar-ingrediente');
			// Llamar a la función validarCampos cada vez que se cambie un campo
			nombreInput.addEventListener('change', () => validarCampos(formAgregarIngredienteElement));
			precioInput.addEventListener('change', () => validarCampos(formAgregarIngredienteElement));

			// Abrir el selector de imagen al hacer clic en el contenedor de foto
			fotoContainer.addEventListener('click', () => {
				fotoInput.click();
			});

			// Función para manejar el envío del formulario para agregar un ingrediente
			formAgregarIngredienteElement.addEventListener('submit', async (event) => {
				event.preventDefault();  // Evitar comportamiento por defecto del formulario

				// Verificar si algún campo no es válido
				if (!formAgregarIngredienteElement.checkValidity()) {
					return;  // Si algún campo es inválido, no se envía el formulario
				}

				const formData = new FormData(formAgregarIngredienteElement);
				formData.append('action', 'insert');

				// Añadir los alérgenos seleccionados al FormData
				const selectedAlergenos = [];
				document.querySelectorAll('input[name="alergenos[]"]:checked').forEach(checkbox => {
					selectedAlergenos.push(checkbox.value);  // Añadir el ID del alérgeno
				});
				formData.append('alergenos', JSON.stringify(selectedAlergenos));

				try {
					const response = await fetch('/apiIngrediente', {
						method: 'POST',
						body: formData
					});

					const data = await response.json();

					if (data.success) {
						// Guardar mensaje de éxito en sessionStorage
						sessionStorage.setItem('flash_message', JSON.stringify({
							message: data.message || 'Ingrediente agregado correctamente',
							type: 'success'
						}));
						formAgregarIngredienteElement.reset();
						await obtenerIngredientesYAlergenos();  // Recargar los ingredientes después de agregar uno nuevo
						window.location.href = data.redirect_url || '/ingredientes';  // Redirigir a la página de ingredientes
					} else {
						// Guardar mensaje de error en sessionStorage
						sessionStorage.setItem('flash_message', JSON.stringify({
							message: data.message || 'Hubo un error al agregar el ingrediente',
							type: 'error'
						}));
						window.location.href = '/ingredientes';  // Redirigir a la página de ingredientes
					}
				} catch (error) {
					console.error('Error al enviar la solicitud:', error);
					// Guardar mensaje de error en sessionStorage en caso de fallo
					sessionStorage.setItem('flash_message', JSON.stringify({
						message: 'Hubo un problema al agregar el ingrediente. Intenta nuevamente.',
						type: 'error'
					}));
					window.location.href = '/ingredientes';  // Redirigir a la página de ingredientes
				}
			});

			// Función para cancelar el formulario de agregar ingrediente
			document.querySelector('#cancelar-formulario').addEventListener('click', () => {
				formularioContainer.style.display = 'none';
				ingredientesContainer.style.display = 'block';
				formAgregarIngrediente.style.display = 'block';
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
				return validarCampo(campo, regexPrecio, 'El precio debe ser un valor numérico válido');
			}

			// Función para validar todos los campos
			function validarCampos(formulario) {
				let esValido = true;

				// Validar nombre y precio
				const nombreValido = validarNombre(formulario.querySelector('#nombre'));
				const precioValido = validarPrecio(formulario.querySelector('#precio'));

				// Verificar si ambos campos son válidos
				if (!nombreValido || !precioValido) {
					esValido = false;
				}

				// Habilitar o deshabilitar el botón según la validación
				const guardarBoton = formulario.querySelector('#guardar-ingrediente');
				guardarBoton.disabled = !esValido;

				return esValido;
			}

		});

	</script>

<?php $this->stop(); ?>