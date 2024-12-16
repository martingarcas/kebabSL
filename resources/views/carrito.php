<?php $this->layout('master'); ?>
<?php $this->start('css'); ?>
	<link rel="stylesheet" href="stylesRegister.css">
	<link rel="stylesheet" href="stylesCarrito.css">
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

		<div class="carrito">
			<!-- Encabezado -->
			<header class="carrito-header">
				<h1>Tu Carrito</h1>
				<div>
					<span>Total:</span>
					<span class="total-precio">0.00€</span>
				</div>
				<div class="saldo">
					<span>Saldo actual:</span>
					<span class="total-saldo">50€</span>
				</div>
				<div class="botones-globales">
					<button class="btn vaciar">Vaciar Carrito</button>
					<button class="btn realizar">Realizar Pedido</button>
				</div>
			</header>

			<!-- Lista de líneas de pedido (dinámico) -->
			<section class="lineas-pedido">
				<!-- Las líneas de pedido se generarán dinámicamente aquí -->
			</section>

			<!-- Pie del carrito -->
			<footer class="carrito-footer">
				<div class="total">
					<span>Total:</span>
					<span class="total-precio">0.00€</span>
				</div>
				<div style="width: 100%; text-align: center">
					<button class="btn realizar grande">Realizar Pedido</button>
				</div>
			</footer>
		</div>

	</div>


<?php $this->stop() ?>

<?php $this->start('scripts'); ?>

	<script>
		document.addEventListener('DOMContentLoaded', () => {

			// Aquí se puede definir la lógica para procesar el pedido
			let saldo = document.querySelector('.total-saldo').textContent.split('€')[0];

			// console.log(sessionStorage.getItem('flash_message'))

			// Función para mostrar el carrito
			function mostrarCarrito(saldo) {
				console.log(saldo)
				// Obtener el carrito desde sessionStorage
				const carrito = JSON.parse(sessionStorage.getItem('carrito')) || { lineasPedido: [] };

				// Seleccionar el contenedor del carrito
				const carritoContainer = document.querySelector('.carrito');

				// Encabezado del carrito (estático)
				carritoContainer.innerHTML = `
                <header class="carrito-header">
                    <h1>Tu Carrito</h1>
                    <div class="total">
						Total:
                        <span class="total-precio">${calcularTotalPrecio(carrito.lineasPedido)}€</span>
                    </div>
                    <div class="botones-globales">
                        <button class="btn vaciar">Vaciar Carrito</button>
                        <button class="btn realizar">Realizar Pedido</button>
                    </div>
                </header>

                <section class="lineas-pedido">
                    ${crearLineasPedido(carrito.lineasPedido)}
                </section>

                <footer class="carrito-footer">
                    <div class="total">
						<div>
							<span>Total:</span>
							<span class="total-precio">${calcularTotalPrecio(carrito.lineasPedido)}€</span>
						</div>
						<div class="saldo">
							<span>Saldo actual:</span>
							<span class="total-saldo">${saldo || 50}€</span>
						</div>
                    </div>


					 <div class="direccion-envio">

						<label for="direccionEnvio">Dirección de Envío:</label>
						<select id="direccionEnvio">
							<option value="direccion1" selected>Infanta Pilar, nº 21, Jaén</option>
							<option value="direccion2">Alameda Central, Bloque 3, 2º-B, Jaén</option>
							<option value="direccion3">Infanta Margarita, nº 3, Jaén</option>
						</select>

					</div>

					<div style="width: 100%; text-align: center">
						<button class="btn realizar grande">Realizar Pedido</button>
					</div>
                </footer>
            `;

				// Añadir los eventos a los botones
				document.querySelector('.btn.vaciar').addEventListener('click', vaciarCarrito);
				let buttonsRelizar = document.querySelectorAll('.btn.realizar');
				buttonsRelizar.forEach(btn => {
					btn.addEventListener('click', realizarPedido)
				});

				// Agregar eventos a los botones dinámicos
				document.querySelector('.lineas-pedido').addEventListener('click', function(event) {
					const button = event.target;
					const nombreKebab = button.getAttribute('data-nombre');

					if (button.classList.contains('mas')) {
						incrementarCantidad(nombreKebab);
					} else if (button.classList.contains('menos')) {
						decrementarCantidad(nombreKebab);
					} else if (button.classList.contains('eliminar')) {
						eliminarDelCarrito(nombreKebab);
					}
				});
			}

			// Función para crear las líneas de pedido dinámicamente
			function crearLineasPedido(lineasPedido) {
				return lineasPedido.map(linea => {
					return `
                    <article class="linea-pedido">
                        <div class="info">
                            <h2 class="nombre-kebab">${linea.nombre || 'Custom'}</h2>
                            <p class="ingredientes">Ingredientes: ${linea.ingredientes || ''}</p>
                            <p class="precio">Precio: ${(linea.precio * linea.cantidad).toFixed(2)}€</p>
                        </div>
                        <div class="controles">
                            <div class="cantidad">
                                <button class="btn menos" data-nombre="${linea.nombre}">-</button>
                                <span class="cantidad-numero">${linea.cantidad}</span>
                                <button class="btn mas" data-nombre="${linea.nombre}">+</button>
                            </div>
                            <button class="btn eliminar" data-nombre="${linea.nombre}">Eliminar</button>
                        </div>
                    </article>
                `;
				}).join('');
			}

			// Función para calcular el total del carrito
			function calcularTotalPrecio(lineasPedido) {
				return lineasPedido.reduce((total, linea) => total + (linea.precio * linea.cantidad), 0).toFixed(2);
			}

			// Funciones de manejo del carrito
			function vaciarCarrito() {
				sessionStorage.removeItem('carrito');
				sessionStorage.removeItem('carrito_cantidad');
				// mostrarCarrito();
				window.location.href = '/carrito';
			}

			function eliminarDelCarrito(nombreKebab) {
				let carrito = JSON.parse(sessionStorage.getItem('carrito')) || { lineasPedido: [] };
				carrito.lineasPedido = carrito.lineasPedido.filter(linea => linea.nombre !== nombreKebab);
				sessionStorage.setItem('carrito', JSON.stringify(carrito));

				// Actualizar carrito_cantidad
				let carritoCantidad = carrito.lineasPedido.reduce((total, linea) => total + linea.cantidad, 0);
				sessionStorage.setItem('carrito_cantidad', carritoCantidad.toString());

				// Disparar evento de actualización de carrito
				const evento = new CustomEvent('carritoActualizado', { detail: { carritoCantidad } });
				document.dispatchEvent(evento);

				mostrarCarrito();
			}

			function incrementarCantidad(nombreKebab) {
				let carrito = JSON.parse(sessionStorage.getItem('carrito')) || { lineasPedido: [] };
				const linea = carrito.lineasPedido.find(linea => linea.nombre === nombreKebab);
				if (linea) {
					linea.cantidad += 1;

					// Actualizar la cantidad total en carrito_cantidad
					let carritoCantidad = parseInt(sessionStorage.getItem('carrito_cantidad'), 10) || 0;
					carritoCantidad += 1;
					sessionStorage.setItem('carrito_cantidad', carritoCantidad.toString());

					sessionStorage.setItem('carrito', JSON.stringify(carrito));

					// Disparar evento de actualización de carrito
					const evento = new CustomEvent('carritoActualizado', { detail: { carritoCantidad } });
					document.dispatchEvent(evento);

					mostrarCarrito();
				}
			}

			function decrementarCantidad(nombreKebab) {
				let carrito = JSON.parse(sessionStorage.getItem('carrito')) || { lineasPedido: [] };
				const linea = carrito.lineasPedido.find(linea => linea.nombre === nombreKebab);
				if (linea && linea.cantidad > 1) {
					linea.cantidad -= 1;

					// Actualizar la cantidad total en carrito_cantidad
					let carritoCantidad = parseInt(sessionStorage.getItem('carrito_cantidad'), 10) || 0;
					carritoCantidad -= 1;
					sessionStorage.setItem('carrito_cantidad', carritoCantidad.toString());

					sessionStorage.setItem('carrito', JSON.stringify(carrito));

					// Disparar evento de actualización de carrito
					const evento = new CustomEvent('carritoActualizado', { detail: { carritoCantidad } });
					document.dispatchEvent(evento);

					mostrarCarrito();
				}
			}

			function realizarPedido() {

				let precioTotal = document.querySelector('.total-precio').textContent.split('€')[0];

				saldo -= precioTotal;
				// console.log(saldo)
				sessionStorage.setItem('flash_message', JSON.stringify({
					message: '¡Pedido realizado con éxito!',
					type: 'success'
				}));
				sessionStorage.removeItem('carrito');
				sessionStorage.removeItem('carrito_cantidad');
				// window.location.href = '/carrito'
				mostrarCarrito(saldo);
				// Disparar evento de actualización de carrito
				let carritoCantidad = 0;
				const evento = new CustomEvent('carritoActualizado', { detail: { carritoCantidad } });
				document.dispatchEvent(evento);
			}

			mostrarCarrito(saldo);

		});
	</script>


<?php $this->stop(); ?>