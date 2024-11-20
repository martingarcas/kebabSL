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
			<div id="formulario-ingrediente" class="formulario-ingrediente">
				<form action="/apiIngrediente" method="POST" id="form-agregar-ingrediente" enctype="multipart/form-data">
					<h2>Editar Ingrediente</h2>

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

					<button id="editar-ingrediente" class="btn" type="submit" disabled>Guardar Ingrediente</button>
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
			const tarjetasContainer = document.querySelector('.tarjetas-container');
			const formularioContainer = document.querySelector('#formulario-container');
			const ingredientesContainer = document.querySelector('#ingredientes-container');
			const formAgregarIngrediente = document.querySelector('.card-agregar');
			const alergenosContainer = document.querySelector('#alergenos-container');
			const spinner = document.getElementById('spinner');

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

					// Crear las tarjetas de ingredientes y mostrarlas
					ingredientes.reverse().forEach(ingrediente => {
						const card = crearCardIngrediente(ingrediente, alergenos);
						tarjetasContainer.appendChild(card);
					});

					// Crear los checkboxes para los alérgenos
					agregarAlergenosCheckBox(alergenos);

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

			const validarNombre = () => {

				if (nombreInput.value.trim() === '') {
					limpiarErrores(nombreInput);
					mostrarError(nombreInput, 'El nombre es obligatorio');
					return false;  // El nombre no es válido
				} else {
					limpiarErrores(nombreInput);
					return true;  // El nombre es válido
				}
			};

			const validarPrecio = () => {
				const precio = precioInput.value.trim();
				const regexPrecio = /^[0-9]+(\.[0-9]{1,2})?$/;

				// Si el campo está vacío, muestra únicamente este error
				if (!precio) {
					limpiarErrores(precioInput); // Asegurar que no haya otros errores
					mostrarError(precioInput, 'El precio es obligatorio');
					return false;  // El precio no es válido
				}

				// Limpiar el error de campo vacío antes de verificar si es numérico
				limpiarErrores(precioInput);

				// Verificar si el valor no cumple con el formato numérico
				if (!regexPrecio.test(precio)) {
					mostrarError(precioInput, 'El precio debe ser un valor numérico válido');
					return false;  // El precio no es válido
				} else {
					// Si pasa todas las validaciones, asegurarse de limpiar errores
					limpiarErrores(precioInput);
					return true;  // El precio es válido
				}
			};

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

			// Función para validar los campos y habilitar el botón de guardar
			function validarCampos() {
				let guardarBoton = document.querySelector('#guardar-ingrediente')
				// Verificar si el nombre y el precio son válidos
				const nombreValido = validarNombre();  // Esta función ya debe devolver true o false
				const precioValido = validarPrecio();  // Esta función ya debe devolver true o false

				// Habilitar el botón de guardar solo si ambos son válidos
				if (nombreValido && precioValido) {
					guardarBoton.disabled = false;
				} else {
					guardarBoton.disabled = true;
				}
			}

			// Llamar a la función validarCampos cada vez que se cambie un campo
			nombreInput.addEventListener('change', validarCampos);
			precioInput.addEventListener('change', validarCampos);

			// Abrir el selector de imagen al hacer clic en el contenedor de foto
			fotoContainer.addEventListener('click', () => {
				fotoInput.click();
			});

			// Función para manejar el envío del formulario para agregar un ingrediente
			const formAgregarIngredienteElement = document.querySelector('#form-agregar-ingrediente');
			formAgregarIngredienteElement.addEventListener('submit', async (event) => {
				event.preventDefault();  // Evitar comportamiento por defecto del formulario

				// Validar campos
				validarNombre();
				validarPrecio();

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
						obtenerIngredientesYAlergenos();  // Recargar los ingredientes después de agregar uno nuevo
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

		});

	</script>



<?php $this->stop(); ?>