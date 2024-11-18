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
				<form action="/apiIngrediente" method="POST" id="form-agregar-ingrediente">
					<h2>Agregar Ingrediente</h2>

					<!-- Foto -->
					<label for="foto">Foto del Ingrediente:</label>
					<div class="foto-container" id="foto-container">
						<input type="file" id="foto" name="foto" accept="image/*">
						<img src="/img/iconos/agregar.png" alt="Agregar imagen" class="icono-agregar">
						<div id="foto-preview"></div>
					</div>

					<!-- Nombre -->
					<label for="nombre">Nombre:</label>
					<input type="text" id="nombre" name="nombre" required>

					<!-- Precio -->
					<label for="precio">Precio:</label>
					<input type="text" id="precio" name="precio" required>

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
			const formularioContainer = document.querySelector('#formulario-container'); // El contenedor del formulario
			const ingredientesContainer = document.querySelector('#ingredientes-container'); // El contenedor de la lista de ingredientes
			const formAgregarIngrediente = document.querySelector('.card-agregar'); // La tarjeta de agregar ingrediente
			const alergenosContainer = document.querySelector('#alergenos-container'); // El contenedor donde se agregarán los checkboxes

			async function obtenerIngredientesYAlergenos() {
				const formData = new FormData();
				formData.append('action', 'show');

				const response = await fetch('/apiIngrediente', { method: 'POST', body: formData });
				const data = await response.json();
				console.log(data)

				if (data.success) {
					const ingredientes = data.ingredientes;
					const alergenos = data.alergenos;

					// Crear tarjeta para agregar un nuevo ingrediente (siempre la primera)
					const cardAgregar = crearCardAgregar();
					tarjetasContainer.appendChild(cardAgregar);

					// Crear tarjetas de ingredientes
					ingredientes.forEach(ingrediente => {
						const card = crearCardIngrediente(ingrediente, alergenos);
						tarjetasContainer.appendChild(card);
					});

					// Llenar los checkboxes de los alérgenos en el formulario
					agregarAlergenosCheckBox(alergenos);
				}
			}

			// Función para crear los checkboxes de alérgenos
			function agregarAlergenosCheckBox(alergenos) {
				alergenosContainer.innerHTML = ''; // Limpiar el contenedor antes de agregar los nuevos checkboxes

				alergenos.forEach(alergeno => {
					// Crear el contenedor para cada checkbox
					const div = document.createElement('div');
					div.classList.add('alergeno-checkbox');

					// Crear el checkbox
					const checkbox = document.createElement('input');
					checkbox.type = 'checkbox';
					checkbox.id = `alergeno-${alergeno.id}`;
					checkbox.name = 'alergenos[]'; // Esto es importante para enviar varios valores
					checkbox.value = alergeno.id;

					// Crear la etiqueta para el checkbox
					const label = document.createElement('label');
					label.setAttribute('for', `alergeno-${alergeno.id}`);
					label.textContent = alergeno.nombre;

					// Agregar el checkbox y la etiqueta al contenedor
					div.appendChild(checkbox);
					div.appendChild(label);

					// Agregar el contenedor al contenedor de alérgenos
					alergenosContainer.appendChild(div);
				});
			}

			// Mostrar vista previa de la imagen seleccionada
			const fotoInput = document.querySelector('#foto');
			const fotoContainer = document.querySelector('#foto-container');
			const fotoPreview = document.querySelector('#foto-preview');

			fotoInput.addEventListener('change', (event) => {
				const file = event.target.files[0];
				if (file) {
					// Crear un objeto URL para la imagen
					const reader = new FileReader();

					reader.onload = function(e) {
						// Mostrar la imagen seleccionada
						fotoPreview.innerHTML = `<img src="${e.target.result}" alt="Imagen seleccionada" class="foto-preview-img">`;
					};

					// Leer el archivo como una URL de imagen
					reader.readAsDataURL(file);
				}
			});

			// Abrir el selector de archivos al hacer clic en la cajita
			fotoContainer.addEventListener('click', () => {
				fotoInput.click();
			});

			function crearCardAgregar() {
				const card = document.createElement('div');
				card.classList.add('ingrediente-card', 'card-agregar');

				// Imagen de agregar (ícono de '+')
				const iconoAgregar = document.createElement('img');
				iconoAgregar.classList.add('icono-agregar');
				iconoAgregar.src = '/img/iconos/agregar.png';  // Cambia esto por el icono que prefieras
				iconoAgregar.alt = 'Agregar nuevo ingrediente';
				card.appendChild(iconoAgregar);

				// Título de la tarjeta
				const texto = document.createElement('h3');
				texto.textContent = 'Agregar Ingrediente';
				card.appendChild(texto);

				// Manejar el evento de click para mostrar el formulario
				card.addEventListener('click', () => {
					// Ocultar la lista de ingredientes
					ingredientesContainer.style.display = 'none';
					// Mostrar el formulario de agregar ingrediente
					formularioContainer.style.display = 'block';
					// Ocultar la tarjeta de agregar ingrediente
					formAgregarIngrediente.style.display = 'none';
				});

				return card;
			}

			function crearCardIngrediente(ingrediente, alergenos) {
				const card = document.createElement('div');
				card.classList.add('ingrediente-card');

				// Foto del ingrediente
				const img = document.createElement('img');
				img.classList.add('ingrediente-img');
				img.src = ingrediente.foto;
				img.alt = ingrediente.nombre;
				card.appendChild(img);

				// Nombre del ingrediente
				const nombre = document.createElement('h3');
				nombre.textContent = ingrediente.nombre;
				card.appendChild(nombre);

				// Precio del ingrediente
				const precio = document.createElement('p');
				precio.classList.add('precio');
				precio.textContent = `${ingrediente.precio}€`;
				card.appendChild(precio);

				// Contenedor de alérgenos
				const alergenosContainer = document.createElement('div');
				alergenosContainer.classList.add('alergenos-container');

				// Verificar si `ingrediente.alergenos` es un objeto
				if (typeof ingrediente.alergenos === 'object' && ingrediente.alergenos !== null) {
					// Recorrer las claves de `ingrediente.alergenos` (que son los IDs)
					for (const idAlergeno in ingrediente.alergenos) {
						if (ingrediente.alergenos.hasOwnProperty(idAlergeno)) {
							// Buscar el alérgeno correspondiente usando el ID
							const alergeno = alergenos.find(a => a.id.toString() === idAlergeno);

							if (alergeno) {
								// Crear el elemento de imagen del alérgeno
								const imgAlergeno = document.createElement('img');
								imgAlergeno.classList.add('alergeno-img');
								imgAlergeno.src = alergeno.foto;
								imgAlergeno.alt = alergeno.nombre;

								// Crear un tooltip con el nombre del alérgeno
								const tooltip = document.createElement('span');
								tooltip.classList.add('tooltip');
								tooltip.textContent = alergeno.nombre;

								// Añadir la imagen y el tooltip al contenedor de alérgenos
								alergenosContainer.appendChild(imgAlergeno);
								alergenosContainer.appendChild(tooltip);
							}
						}
					}
				} else {
					// Si no hay alérgenos, mostrar un mensaje
					const noAlergenos = document.createElement('span');
					noAlergenos.classList.add('no-alergenos');
					noAlergenos.textContent = 'Sin alérgenos';
					alergenosContainer.appendChild(noAlergenos);
				}

				// Añadir el contenedor de alérgenos a la tarjeta
				card.appendChild(alergenosContainer);

				return card;
			}


			// Manejar el botón "Cancelar" del formulario
			document.querySelector('#cancelar-formulario').addEventListener('click', () => {
				// Ocultar el formulario
				formularioContainer.style.display = 'none';
				// Volver a mostrar la lista de ingredientes
				ingredientesContainer.style.display = 'block';
				// Volver a mostrar la tarjeta de agregar ingrediente
				formAgregarIngrediente.style.display = 'block';
			});

			// Obtener los ingredientes y alérgenos al cargar la página
			obtenerIngredientesYAlergenos();
		});
	</script>

<?php $this->stop(); ?>