<?php $this->layout('master'); ?>
<?php $this->start('css'); ?>
	<link rel="stylesheet" href="stylesRegister.css">
	<link rel="stylesheet" href="stylesCarta.css">
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
			<h2>KEBABS DE LA CASA</h2>
			<div class="tarjetas-container"></div>
		</div>

		<!-- Contenedor del formulario para agregar kebabs -->
		<div class="form-container" id="formulario-container" style="display: none;">
			<div id="formulario-kebab" class="formulario-kebab">
				<form id="form-agregar-kebab" enctype="multipart/form-data">
					<h2>Kebab Personalizado</h2>

					<fieldset class="header-kebab">
						<!-- Foto -->
						<!--						<label for="foto">Foto del Kebab:</label>-->
						<div class="inputs-kebab">

							<div class="foto-container" id="foto-container">
								<img src="/img/kebabs/kebabcustom.jpeg" alt="Imagen seleccionada" class="foto-preview-img">
							</div>

						</div>

						<div class="inputs-kebab">

							<!-- Nombre -->
							<label for="nombre">Nombre:</label>
							<input type="text" id="nombre" name="nombre">

							<!-- Precio -->
							<!-- Indicador de Precio Base -->
							<div class="precios">
								<div id="precio-base-container">
									<label>Precio Base (pan incluido): </label>
									<span id="precio-base">2€</span>
								</div>

								<!-- Indicador de Precio Total Dinámico -->
								<div id="precio-total-container">
									<label>Precio Total: </label>
									<span id="precio-total-crear">2.00€</span>
								</div>
							</div>

						</div>

						<div class="inputs-kebab">
							<div class="cantidad">
								<label>Cantidad: </label>
								<input type="number" min="1" class="input-cantidad" value="1">
							</div>
						</div>
					</fieldset>

					<!-- Ingredientes (Checkboxs) -->
					<fieldset class="fieldset-ingredientes">
						<legend>Ingredientes:</legend>
						<div id="ingredientes-container">
							<!-- Aquí se llenarán los checkboxes de ingredientes dinámicamente -->
						</div>
					</fieldset>

					<button id="guardar-kebab" class="btn">Añadir al carrito</button>
					<button type="button" id="cancelar-formulario">Cancelar</button>
				</form>
			</div>
		</div>

		<!-- Contenedor del formulario para editar Kebabs -->
		<div class="form-container" id="formulario-container-edit" style="display: none;">
			<div id="formulario-kebab-edit" class="formulario-kebab-edit">
				<form id="form-edit-kebab" enctype="multipart/form-data">
					<h2>Editar Kebab</h2>

					<fieldset class="header-kebab">
						<!-- Foto -->
						<div class="foto-container" id="foto-container-edit">
							<div id="foto-preview"></div>
						</div>

						<div class="inputs-kebab">

							<!-- Nombre -->
							<div id="nombre-base-container">
								<label for="nombre">Nombre:</label>
								<span id="nombre" name="nombre"></span>
							</div>

							<!-- Precio -->
							<!-- Indicador de Precio Base -->
							<div class="precios">
								<div id="precio-base-container">
									<label>Precio Base (pan incluido): </label>
									<span id="precio-base">2€</span>
								</div>

								<!-- Indicador de Precio Total Dinámico -->
								<div id="precio-total-container">
									<label>Precio Total: </label>
									<span id="precio-total-edit">2.00€</span>
								</div>
							</div>

						</div>

						<div class="inputs-kebab">
							<div class="cantidad">
								<label>Cantidad: </label>
								<input type="number" min="1" class="input-cantidad" value="1">
							</div>
						</div>
					</fieldset>

					<!-- Ingredientes (Checkboxs) -->
					<fieldset class="fieldset-ingredientes">
						<legend>Ingredientes:</legend>
						<div id="ingredientes-container-edit">
							<!-- Aquí se llenarán los checkboxes de ingredientes dinámicamente -->
						</div>
					</fieldset>

					<button id="edit-kebab" class="btn" type="submit">Añadir al carrito</button>
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
			const PRECIO_BASE = 2.0; // Precio base fijo por el pan

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
						const card = crearCardKebab(kebab, ingredientes);
						card.kebab = ++contador;
						tarjetasContainer.appendChild(card);
					});

					// Crear los checkboxes para los ingredientes
					agregarIngredientesCheckBox(ingredientes);

					/* ------------------------------------------------- */
					/*  SECCIÓN 3: EDICIÓN DE UN KEBAB" */
					/* ------------------------------------------------- */
					let btnPersonalizar = document.querySelectorAll(".btn.btn-personalizar")
					// Usar forEach para iterar sobre todos los elementos
					btnPersonalizar.forEach(function(btn) {
						btn.addEventListener('click', function (e) {
							let card = e.target.closest('#kebab-card');
							let index = card.kebab;  // Asegúrate de que 'kebab' esté definido en el objeto
							let kebabEdit = kebabs[index];
							mostrarEditForm(kebabEdit);
							saveOriginalValues();
						});
					});

					function mostrarEditForm(kebab) {

						formularioContainerEdit.id = kebab.id;

						// console.log(kebab.ingredientes)

						kebabsContainer.style.display 	= 'none';
						formularioContainerEdit.style.display 	= 'block';

						const fotoPreview 	= formularioContainerEdit.querySelector('#foto-preview');
						const nombreInput 	= formularioContainerEdit.querySelector('#nombre');
						const precioInput 	= formularioContainerEdit.querySelector('#precio-total-edit');

						// Añadir los eventos change para cada campo de entrada
						fotoPreview.innerHTML = `<img src="${kebab.foto}" alt="Imagen seleccionada" class="foto-preview-img">`;

						nombreInput.textContent = kebab.nombre;
						precioInput.textContent = kebab.precio;

						// Crear los checkboxes para los ingredientes
						agregarKebabsEdit(ingredientes, kebab);

						// Añadir event listener para los checkboxes de ingredientes
						document.querySelectorAll('input[name="ingredientes[]"]').forEach(checkbox => {
							checkbox.addEventListener('change', function() {
								// Aquí se llama la función de actualización solo cuando el checkbox cambia
								actualizarPrecioTotalEdit(kebab.precio); // Asegúrate de que kebab.precio está definido
							});
						});

						// Función para cancelar el formulario de agregar kebab
						formularioContainerEdit.querySelector('#cancelar-formulario').addEventListener('click', () => {
							formularioContainerEdit.style.display = 'none';
							kebabsContainer.style.display = 'block';
						});

					}

					// Función para agregar los checkboxes de ingredientes
					function agregarKebabsEdit(ingredientes, kebab) {
						const ingredientesSet = new Set(Object.keys(kebab.ingredientes).map(key => String(key))); // IDs como cadenas
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
							// checkbox.addEventListener('change', actualizarPrecioTotalEdit(kebab.precio)); // Llamar a la función de actualización

							// Verificar si el ingrediente ya está asociado al kebab
							if (ingredientesSet.has(String(ingrediente.id))) {
								checkbox.checked = true; // Marcar como seleccionado si aplica
								checkbox.classList.add('ya-seleccionado');
							}

							const label = document.createElement('label');
							label.setAttribute('for', `ingrediente-${ingrediente.id}`);
							label.textContent = ingrediente.nombre;

							div.appendChild(checkbox);
							div.appendChild(label);
							ingredientesContainerEdit.appendChild(div);
						});
					}

				}

			}

			const formEditarKebab 	= document.querySelector('#form-edit-kebab');
			const nombreEdit 		= formEditarKebab.querySelector('#nombre');
			const cantidadEdit 		= formEditarKebab.querySelector('.input-cantidad');
			const btnUpdate 		= formEditarKebab.querySelector('#edit-kebab');

			btnUpdate.addEventListener('click', function(e) {
				e.preventDefault();
				const nombreKebab = nombreEdit.textContent;  // Suponiendo que "kebab" tiene el nombre
				const cantidadSeleccionada = cantidadEdit.value;  // Obtener la cantidad seleccionada del input

				// Llamar a la función addToCart, pasando el nombre del kebab y la cantidad seleccionada
				addToCart(nombreKebab, cantidadSeleccionada);
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
				texto.textContent = 'Kebab Personalizado';
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
			function crearCardKebab(kebab, ingredientes) {
				const card = document.createElement('div');
				card.classList.add('kebab-card');
				card.id = 'kebab-card';

				const nombre = document.createElement('h3');
				nombre.textContent = kebab.nombre;
				card.appendChild(nombre);

				const datosKebab = document.createElement('div');
				datosKebab.classList.add('datos-kebab');

				const datosImg = document.createElement('div');
				datosImg.classList.add('datos-asociados');

				const datosAsociados = document.createElement('div');
				datosAsociados.classList.add('datos-asociados');

				const img = document.createElement('img');
				img.classList.add('kebab-img');
				img.src = kebab.foto;
				img.alt = kebab.nombre;
				datosImg.appendChild(img);
				datosKebab.appendChild(datosImg);

				const precio = document.createElement('p');
				precio.classList.add('precio');
				precio.textContent = `${kebab.precio}€`;

				// Crear la sección de ingredientes
				const ingredientesContainer = document.createElement('div');
				ingredientesContainer.classList.add('ingredientes-container');

				if (typeof kebab.ingredientes === 'object' && kebab.ingredientes !== null) {
					const ingredientesTexto = document.createElement('p');
					ingredientesTexto.classList.add('ingredientes-texto');

					const nombresIngredientes = [];
					const alergenosContainer = document.createElement('div');
					alergenosContainer.classList.add('alergenos-container');

					// Usamos un Set para evitar alérgenos duplicados
					const alergenosYaAñadidos = new Set();

					for (const idIngrediente in kebab.ingredientes) {
						if (kebab.ingredientes.hasOwnProperty(idIngrediente)) {
							const ingrediente = ingredientes.find(a => a.id.toString() === idIngrediente);
							if (ingrediente) {
								nombresIngredientes.push(ingrediente.nombre);

								if (typeof ingrediente.alergenos === 'object' && ingrediente.alergenos !== null) {
									for (const idAlergeno in ingrediente.alergenos) {
										if (ingrediente.alergenos.hasOwnProperty(idAlergeno)) {
											const alergeno = ingrediente.alergenos[idAlergeno];

											// Comprobar si el alérgeno ya ha sido añadido
											if (!alergenosYaAñadidos.has(alergeno.nombre)) {
												alergenosYaAñadidos.add(alergeno.nombre);  // Agregar alérgeno al Set

												const imgAlergeno = document.createElement('img');
												imgAlergeno.classList.add('alergeno-img');
												imgAlergeno.src = alergeno.foto;
												imgAlergeno.alt = alergeno.nombre;

												const tooltip = document.createElement('span');
												tooltip.classList.add('tooltip');
												tooltip.textContent = alergeno.nombre;

												// Agregar el alérgeno al contenedor
												alergenosContainer.appendChild(imgAlergeno);
												alergenosContainer.appendChild(tooltip);
											}
										}
									}
								}
							}
						}
					}

					ingredientesTexto.textContent = nombresIngredientes.join(', ');
					ingredientesContainer.appendChild(ingredientesTexto);
					datosAsociados.appendChild(ingredientesContainer);
					datosAsociados.appendChild(precio);
					datosAsociados.appendChild(alergenosContainer);
				} else {
					const noIngredientes = document.createElement('span');
					noIngredientes.classList.add('no-ingredientes');
					noIngredientes.textContent = 'Sin Ingredientes';
					ingredientesContainer.appendChild(noIngredientes);
					datosAsociados.appendChild(ingredientesContainer);
				}

				datosKebab.appendChild(datosAsociados);
				card.appendChild(datosKebab);

				// Crear el contenedor final con los botones y el input
				const actionsContainer = document.createElement('div');
				actionsContainer.classList.add('actions-container');

				const personalizarButton = document.createElement('button');
				personalizarButton.textContent = 'Personalizar';
				personalizarButton.classList.add('btn', 'btn-personalizar');

				const addToCartContainer = document.createElement('div');
				addToCartContainer.classList.add('add-to-cart-container');

				const cantidadInput = document.createElement('input');
				cantidadInput.type = 'number';
				cantidadInput.value = 1;
				cantidadInput.min = 1;
				cantidadInput.classList.add('input-cantidad');

				const addToCartButton = document.createElement('button');
				addToCartButton.textContent = 'Añadir al carrito';
				addToCartButton.classList.add('btn', 'btn-add-to-cart');
				// Cuando el usuario haga clic en "Añadir al carrito", obtenemos la cantidad y el nombre del kebab
				addToCartButton.addEventListener('click', function() {
					const nombreKebab = kebab.nombre;  // Suponiendo que "kebab" tiene el nombre
					const cantidadSeleccionada = cantidadInput.value;  // Obtener la cantidad seleccionada del input

					// Llamar a la función addToCart, pasando el nombre del kebab y la cantidad seleccionada
					addToCart(nombreKebab, cantidadSeleccionada);
				});

				addToCartContainer.appendChild(cantidadInput);
				addToCartContainer.appendChild(addToCartButton);

				actionsContainer.appendChild(personalizarButton);
				actionsContainer.appendChild(addToCartContainer);

				// Separar el contenedor final con un borde fino
				const separator = document.createElement('hr');
				separator.classList.add('card-separator');

				card.appendChild(separator);
				card.appendChild(actionsContainer);

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
			const nombreInput = document.querySelector('#nombre');
			const cantidad = formAgregarKebabElement.querySelector('.input-cantidad');
			const guardarBoton = formAgregarKebabElement.querySelector('#guardar-kebab');


			guardarBoton.addEventListener('click', function(e) {
				e.preventDefault();
				const nombreKebab = nombreInput.value;  // Suponiendo que "kebab" tiene el nombre
				const cantidadSeleccionada = cantidad.value;  // Obtener la cantidad seleccionada del input

				// Llamar a la función addToCart, pasando el nombre del kebab y la cantidad seleccionada
				addToCart(nombreKebab, cantidadSeleccionada);
			});


			// Función para cancelar el formulario de agregar ingrediente
			document.querySelector('#cancelar-formulario').addEventListener('click', () => {
				formularioContainer.style.display = 'none';
				ingredientesContainer.style.display = 'flex';
				kebabsContainer.style.display = 'block';
			});

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

			// Función para actualizar el precio total dinámicamente
			// function actualizarPrecioTotalEdit(precio) {
			// 	let preciosIngredientesSeleccionados = [];
			// 	let precioMaximo = 0;
			// 	let precioTotal = parseFloat(precio) || 0; // Aseguramos que precioTotal sea un número
			//
			// 	// Obtener todos los checkboxes de ingredientes
			// 	let ingredientes = document.querySelectorAll('input[name="ingredientes[]"]');
			//
			// 	// Iteramos sobre todos los checkboxes
			// 	ingredientes.forEach(checkbox => {
			// 		// Obtener el precio del ingrediente del atributo 'data-precio'
			// 		const precioIngrediente = parseFloat(checkbox.precio || 0);
			//
			// 		// Si el ingrediente está marcado
			// 		if (checkbox.checked) {
			// 			// Si el ingrediente no ha sido marcado previamente, lo agregamos
			// 			if (!checkbox.classList.contains('ya-seleccionado')) {
			// 				// Añadir el precio al total
			// 				precioTotal += precioIngrediente;
			// 			}
			// 		}
			// 	});
			//
			// 	// Para obtener el precio máximo de los ingredientes seleccionados (solo los nuevos)
			// 	preciosIngredientesSeleccionados = Array.from(ingredientes).filter(checkbox => checkbox.checked && !checkbox.classList.contains('ya-seleccionado')).map(checkbox => parseFloat(checkbox.getAttribute('data-precio') || 0));
			//
			// 	if (preciosIngredientesSeleccionados.length > 0) {
			// 		precioMaximo = Math.max(...preciosIngredientesSeleccionados);
			// 	}
			//
			// 	// Sumamos el precio máximo al total (si existe un precio máximo)
			// 	if (precioMaximo > 0) {
			// 		precioTotal += precioMaximo;
			// 	}
			//
			// 	// Actualizar el texto del precio total en el DOM
			// 	const precioTotalElementoEdit = document.querySelector('#precio-total-edit');
			// 	if (precioTotalElementoEdit) {
			// 		precioTotalElementoEdit.textContent = `${precioTotal.toFixed(2)}€`;
			// 	}
			// }

			// Función para actualizar el precio total dinámicamente
			function actualizarPrecioTotalEdit(precio) {
				let preciosIngredientesSeleccionados = [];
				let ingredientesNuevos = 0; // Contador de ingredientes nuevos
				let precioTotal = parseFloat(precio) || 0; // Aseguramos que precioTotal sea un número

				// Obtener todos los checkboxes de ingredientes
				let ingredientes = document.querySelectorAll('input[name="ingredientes[]"]');

				// Iteramos sobre todos los checkboxes
				ingredientes.forEach(checkbox => {
					// Obtener el precio del ingrediente del atributo 'data-precio'
					const precioIngrediente = parseFloat(checkbox.precio || 0);

					// Si el ingrediente está marcado
					if (checkbox.checked) {
						// Si el ingrediente no ha sido marcado previamente, lo contamos como nuevo
						if (!checkbox.classList.contains('ya-seleccionado')) {
							ingredientesNuevos++; // Incrementamos el contador de ingredientes nuevos
						}

						// Guardamos el precio de todos los ingredientes seleccionados (nuevos y antiguos)
						preciosIngredientesSeleccionados.push(precioIngrediente);
					}
				});

				// Ordenamos los precios de mayor a menor
				preciosIngredientesSeleccionados.sort((a, b) => b - a);

				// Seleccionamos los N ingredientes más caros según el número de ingredientes nuevos añadidos
				const ingredientesMasCaros = preciosIngredientesSeleccionados.slice(0, ingredientesNuevos);

				// Sumamos los precios de los ingredientes más caros seleccionados
				const sumaIngredientesNuevos = ingredientesMasCaros.reduce((suma, precio) => suma + precio, 0);

				// Sumamos la diferencia al precio total
				precioTotal += sumaIngredientesNuevos;

				// Actualizar el texto del precio total en el DOM
				const precioTotalElementoEdit = document.querySelector('#precio-total-edit');
				if (precioTotalElementoEdit) {
					precioTotalElementoEdit.textContent = `${precioTotal.toFixed(2)}€`;
				}
			}




			// Función para agregar al carrito
			function addToCart(nombreKebab, cantidadSeleccionada) {
				// Mostrar un mensaje con el nombre del kebab y la cantidad seleccionada
				alert(`El kebab "${nombreKebab}" ha sido añadido correctamente al carrito. Cantidad: ${cantidadSeleccionada} unidad(es).`);

				// Aquí puedes agregar el kebab al carrito. Suponiendo que tienes una variable carrito:
				// carrito.push({ nombre: nombreKebab, cantidad: cantidadSeleccionada });

				// O actualizar el carrito visualmente:
				// actualizarCarrito();
			}

			// Función para actualizar el carrito visualmente (ejemplo)
			function actualizarCarrito() {
				// Aquí puedes actualizar el número de items en el carrito o hacer cualquier otra cosa.
				const numeroItemsCarrito = carrito.length;  // Suponiendo que "carrito" es un array global.
				document.querySelector('#carrito-cantidad').textContent = numeroItemsCarrito;
			}


		});

	</script>

<?php $this->stop(); ?>