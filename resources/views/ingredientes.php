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
							<label for="precio">Precio:</label>
							<input type="text" id="precio" name="precio" required>

						</div>
					</fieldset>

					<!-- Alergenos (Checkboxs) -->
					<fieldset class="fieldset-alergenos">
						<legend>Alergenos:</legend>
						<div id="alergenos-container">
							<!-- Aquí se llenarán los checkboxes de alérgenos dinámicamente -->
						</div>
					</fieldset>

					<button type="submit">Guardar Ingrediente</button>
					<button type="button" id="cancelar-formulario">Cancelar</button>
				</form>
			</div>
		</div>
	</div>

<?php $this->stop() ?>

<?php $this->start('scripts'); ?>

	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const tarjetasContainer = document.querySelector('.tarjetas-container');
			const formularioContainer = document.querySelector('#formulario-container');
			const ingredientesContainer = document.querySelector('#ingredientes-container');
			const formAgregarIngrediente = document.querySelector('.card-agregar');
			const alergenosContainer = document.querySelector('#alergenos-container');

			async function obtenerIngredientesYAlergenos() {
				const formData = new FormData();
				formData.append('action', 'show');

				const response = await fetch('/apiIngrediente', { method: 'POST', body: formData });
				const data = await response.json();

				if (data.success) {
					const ingredientes = data.ingredientes;
					const alergenos = data.alergenos;

					const cardAgregar = crearCardAgregar();
					tarjetasContainer.appendChild(cardAgregar);

					ingredientes.reverse().forEach(ingrediente => {
						const card = crearCardIngrediente(ingrediente, alergenos);
						tarjetasContainer.appendChild(card);
					});

					agregarAlergenosCheckBox(alergenos);
				}
			}

			function agregarAlergenosCheckBox(alergenos) {
				alergenosContainer.innerHTML = '';
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

			const fotoInput = document.querySelector('#foto');
			const fotoContainer = document.querySelector('#foto-container');
			const fotoPreview = document.querySelector('#foto-preview');

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

			fotoContainer.addEventListener('click', () => {
				fotoInput.click();
			});

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
				card.addEventListener('click', () => {
					ingredientesContainer.style.display = 'none';
					formularioContainer.style.display = 'block';
					formAgregarIngrediente.style.display = 'none';
				});
				return card;
			}

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

			document.querySelector('#cancelar-formulario').addEventListener('click', () => {
				formularioContainer.style.display = 'none';
				ingredientesContainer.style.display = 'block';
				formAgregarIngrediente.style.display = 'block';
			});

			const formAgregarIngredienteElement = document.querySelector('#form-agregar-ingrediente');
			formAgregarIngredienteElement.addEventListener('submit', async (event) => {
				event.preventDefault();  // Evitar el comportamiento por defecto

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
						obtenerIngredientesYAlergenos();
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

			obtenerIngredientesYAlergenos();
		});
	</script>



<?php $this->stop(); ?>