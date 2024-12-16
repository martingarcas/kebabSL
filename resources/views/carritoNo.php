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

	<div class="ccc-modal-overlay"></div>

	<div class="form-wrapper">

		<div class="container">
			<span class="close">&times;</span>
			<div id="Checkout" class="inline">
				<h1>Pay Invoice</h1>
				<div class="card-row">
					<span class="visa"></span>
					<span class="mastercard"></span>
					<span class="amex"></span>
					<span class="discover"></span>
				</div>
				<form>
					<div class="form-group">
						<label for="PaymentAmount">Payment amount</label>
						<div class="amount-placeholder">
							<span></span>
							<span class="total-payment"></span>
						</div>
					</div>
					<div class="form-group">
						<label or="NameOnCard">Name on card</label>
						<input id="NameOnCard" class="form-control" type="text" maxlength="255" required>
					</div>
					<div class="form-group">
						<label for="CreditCardNumber">Card number</label>
						<input id="CreditCardNumber" class="null card-image form-control" type="text" required>
					</div>
					<div class="expiry-date-group form-group">
						<label for="ExpiryDate">Expiry date</label>
						<input id="ExpiryDate" class="form-control" type="text" placeholder="MM / YY" maxlength="7" required>
					</div>
					<div class="security-code-group form-group">
						<label for="SecurityCode">Security code</label>
						<div class="input-container" >
							<input id="SecurityCode" class="form-control" type="text" required>
						</div>
					</div>
					<div class="zip-code-group form-group">
						<label for="ZIPCode">ZIP/Postal code</label>
						<div class="input-container">
							<input id="ZIPCode" class="form-control" type="text" maxlength="10" required>
						</div>
					</div>
					<button id="PayButton" class="btn btn-block btn-success submit-button">
						<span class="submit-button-lock"></span>
						<span class="total-payment"></span>
					</button>
				</form>
			</div>
		</div>

		<div class="carrito">
			<!-- Encabezado -->
			<header class="carrito-header">
				<h1>Tu Carrito</h1>
				<div class="total">
					<span>Total:</span>
					<span class="total-precio">€0.00</span>
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
				<div class="total"  style="display: block !important;">
					<span>Total:</span>
					<span class="total-precio">€0.00</span>
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

			// console.log(sessionStorage.getItem('flash_message'))

			// Función para mostrar el carrito
			function mostrarCarrito() {
				// Obtener el carrito desde sessionStorage
				const carrito = JSON.parse(sessionStorage.getItem('carrito')) || { lineasPedido: [] };

				// Seleccionar el contenedor del carrito
				const carritoContainer = document.querySelector('.carrito');

				// Encabezado del carrito (estático)
				carritoContainer.innerHTML = `
                <header class="carrito-header">
                    <h1>Tu Carrito</h1>
                    <div class="total">
						<span>Total:</span>
                        <span class="total-precio">€${calcularTotalPrecio(carrito.lineasPedido)}</span>
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
                    <div class="total" style="display: block !important;">
                        <span>Total:</span>
                        <span class="total-precio">€${calcularTotalPrecio(carrito.lineasPedido)}</span>
                    </div>

					 <div class="direccion-envio" style="display: flex; flex-wrap: wrap; gap: 18px; align-items: baseline">
						<div>
							<label for="email" style="margin-bottom: 8px;">Email:</label>
							<input type="email" id="email" placeholder="Introduce tu email" style="width:inherit; padding: 8px; font-size: 12px">
						</div>
						<div>
							<label for="direccion" style="margin-bottom: 8px;">Dirección:</label>
							<input type="text" id="direccion" placeholder="Introduce tu dirección" style="width:inherit; padding: 8px; font-size: 12px">
						</div>
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
					btn.addEventListener('click', openModalPayment)
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
                            <p class="precio">Precio: €${(linea.precio * linea.cantidad).toFixed(2)}</p>
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
				window.location.href = '/carritoNo';
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

			let pagoModal = document.querySelector('.container');
			var span = document.getElementsByClassName("close")[0];
			let totalPayments = document.querySelectorAll('.total-payment');
			// Cerrar la modal cuando se hace clic en la "x"
			span.onclick = function() {
				pagoModal.style.display = "none";
				document.querySelector('body').style.overflow = "auto";
				document.querySelector('.ccc-modal-overlay').style.display = 'none';
			}

			function realizarPedido() {

				// Aquí se puede definir la lógica para procesar el pedido
				sessionStorage.setItem('flash_message', JSON.stringify({
					message: '¡Pedido realizado con éxito, revisa los datos en tu correo!',
					type: 'success'
				}));
				sessionStorage.removeItem('carrito');
				sessionStorage.removeItem('carrito_cantidad');
				window.location.href = '/carritoNo';
			}
			
			function openModalPayment() {
				let totalPedido = document.querySelector('.total-precio').textContent.split('€')[1];
				console.log(totalPedido)
				totalPayments.forEach(totalPayment => {
					totalPayment.textContent = totalPedido + '€';
				});
				document.querySelector('body').style.overflow = 'hidden';
				document.querySelector('.ccc-modal-overlay').style.display = 'block';
				pagoModal.style.display = "block";
			}

			document.querySelector('#PayButton').addEventListener('click', function (e) {
				e.preventDefault();
				realizarPedido();
			});

			mostrarCarrito();

		});
	</script>


<?php $this->stop(); ?>