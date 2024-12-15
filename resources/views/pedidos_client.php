<?php $this->layout('master'); ?>
<?php $this->start('css'); ?>

	<style>
		/* Reset básico de estilos */
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: Arial, sans-serif;
			background-color: #f4f4f4;
			color: #333;
			line-height: 1.6;
		}

		/* Estilos para la ventana modal */
		.modal {
			display: none;
			position: fixed;
			z-index: 1;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
			justify-content: center;
			align-items: center;
		}
		.modal-content {
			background-color: #fff;
			padding: 20px;
			border-radius: 5px;
			width: 80%;
			max-width: 500px;
			position: relative;
		}
		.close {
			position: absolute;
			right: 10px;
			top: 10px;
			font-size: 20px;
			cursor: pointer;
		}

		.wrapper {
			padding: 24px;
		}

		/* Estilos para los estados de los pedidos */
		.estado-cancelado {
			background-color: #ffcccc; /* Rojo claro para Recibido */
		}

		.estado-preparando {
			background-color: #add8e6; /* Azul claro para En preparación */
		}

		.estado-enviado {
			background-color: #87ceeb; /* Azul más oscuro para Enviado */
		}

		.estado-completado {
			background-color: #d4edda; /* Verde para Completado */
		}

		/* Estilo para la sección principal */
		.estadisticas {
			margin: 20px auto;
			padding: 20px;
			background-color: #fff;
			border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		}

		/* Estilos del encabezado */
		header {
			text-align: center;
			margin-bottom: 20px;
		}

		header h1 {
			font-size: 2rem;
			color: #333;
		}

		.filtros {
			display: flex;
			justify-content: center;
			gap: 42px;
			margin-top: 10px;
		}

		.filtro {
			display: flex;
			gap: 4px;
			align-items: baseline;
		}

		.filtros label {
			font-size: 1rem;
		}

		.filtros select {
			padding: 8px;
			border: 1px solid #ddd;
			border-radius: 4px;
		}

		/* Estilos de la tabla */
		.tabla-ventas {
			overflow-x: auto;
		}

		.information {
			margin-left: 4px;
			cursor: pointer;
			font-size: 16px;
			vertical-align: baseline;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			text-align: center;
			transition: font-size .5s;
		}

		th, td {
			padding: 10px;
			border: 1px solid #ddd;
			font-size: 0.9rem; /* Tamaño de fuente más pequeño para mayor claridad */
		}

		th {
			background-color: #007BFF; /* Azul para los encabezados */
			color: #fff;
			font-weight: bold;
		}

		tbody tr:nth-child(even) {
			background-color: #fafafa;
		}

		/* Resaltar filas según rentabilidad */
		td[style*="color: red"] {
			background-color: #ffe6e6; /* Rojo claro */
			font-weight: bold;
			color: #e60000; /* Color rojo para el texto */
		}

		td[style*="color: green"] {
			background-color: #e6ffe6; /* Verde claro */
			font-weight: bold;
			color: #008000; /* Color verde para el texto */
		}

		select:focus {
			outline: none;
			border: 2px solid #007BFF; /* Color azul para indicar enfoque */
			box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
		}

		/* Estilos responsivos */
		@media (max-width: 600px) {
			table, th, td {
				font-size: 0.8rem; /* Tamaño de fuente más pequeño en dispositivos más pequeños */
			}

			.filtros {
				flex-direction: column;
				align-items: center;
				gap: 24px;
			}

			.filtros label {
				margin-bottom: 5px;
			}

			/* Adaptación de la tabla en pantallas pequeñas */
			.table-adapt {
				display: block;
				overflow-x: auto; /* Para que la tabla se pueda desplazar horizontalmente */
				margin-top: 20px;
			}

			.table-adapt thead, .table-adapt tbody {
				display: block;
			}

			thead tr {
				display: none !important;
			}

			tr {
				display: flex;
				flex-direction: column;
				border-bottom: 1px solid #eee !important;
				margin-bottom: 10px;
				padding-bottom: 5px;
			}

			td {
				padding-left: 10%;
				border: none;
				position: relative;
				width: 100%;
				display: flex;
				justify-content: space-between; /* Alinea el encabezado y el dato uno al lado del otro */
				align-items: center;
				margin-bottom: 10px; /* Añade espacio entre cada par de dato */
			}

			td:before {
				flex-basis: 35%; /* Asegura que la cabecera ocupe una mayor parte de la línea */
				color: #999;
				font-weight: bold;
				content: attr(data-header);
				margin-right: 10px; /* Añade espacio entre el encabezado y el dato */
			}

			/* Estilos adicionales para los encabezados */
			th {
				display: none;
			}

			/* Extra */
			small { display: block; }
		}
	</style>

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

	<div class="wrapper">
		<section class="estadisticas">
			<header>
				<h1>Panel de Pedidos</h1>
				<div class="filtros">
					<div class="filtro">
						<label for="estadoFilter">Filtrar por estado:</label>
						<select id="estadoFilter" class="input-comun">
							<option value="todos">Todos</option>
							<option value="recibido">Recibido</option>
							<option value="preparando">En preparación</option>
							<option value="enviado">Enviado</option>
							<option value="completado">Completado</option>
							<option value="cancelado">Cancelado</option>
						</select>
					</div>
					<div class="filtro">
						<label for="filtro-fecha">Seleccionar periodo:</label>
						<select id="filtro-fecha" class="input-comun">
							<option value="hoy">Todos</option>
							<option value="semana">Hoy</option>
							<option value="mes">Último mes</option>
						</select>
					</div>
				</div>
			</header>

			<div class="tabla-ventas">
				<table class="table-adapt">
					<thead>
					<tr>
						<th>ID Pedido</th>
						<th>Fecha</th>
						<th>Total (€)</th>
						<th>Estado</th>
						<th>Productos</th>
						<th>Método de Pago</th>
						<th>Incidencias</th>
					</tr>
					</thead>
					<tbody id="pedidoTable">
					<tr data-id="001" data-status="recibido">
						<td data-header="ID Pedido">001</td>
						<td data-header="Fecha">10/12/2024</td>
						<td data-header="Total (€)">45.00</td>
						<td data-header="Estado" class="estado-recibido">
							Recibido
						</td>
						<td data-header="Productos">Kebab Clásico x2<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">Tarjeta</td>
						<td data-header="Incidencias">Ninguna</td>
					</tr>
					<tr data-id="002" data-status="preparando">
						<td data-header="ID Pedido">002</td>
						<td data-header="Fecha">11/12/2024</td>
						<td data-header="Total (€)">30.50</td>
						<td data-header="Estado" class="estado-preparando">
							En preparación
						</td>
						<td data-header="Productos">Kebab Vegetal x1, Bebida x1<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">PayPal</td>
						<td data-header="Incidencias">Demora en el envío</td>
					</tr>
					<tr data-id="003" data-status="enviado">
						<td data-header="ID Pedido">003</td>
						<td data-header="Fecha">12/12/2024</td>
						<td data-header="Total (€)">25.00</td>
						<td data-header="Estado" class="estado-enviado">
							Enviado
						</td>
						<td data-header="Productos">Kebab de Pollo Picante x1<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">Efectivo</td>
						<td data-header="Incidencias">Pedido mal ingresado</td>
					</tr>
					<tr data-id="004" data-status="completado">
						<td data-header="ID Pedido">004</td>
						<td data-header="Fecha">13/12/2024</td>
						<td data-header="Total (€)">50.00</td>
						<td data-header="Estado" class="estado-completado">
							Completado
						</td>
						<td data-header="Productos">Kebab Mixto x2, Postre x1<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">Tarjeta</td>
						<td data-header="Incidencias">Pago aceptado</td>
					</tr>
					<tr data-id="005" data-status="pendiente">
						<td data-header="ID Pedido">005</td>
						<td data-header="Fecha">14/12/2024</td>
						<td data-header="Total (€)">35.00</td>
						<td data-header="Estado" class="estado-pendiente">
							Pendiente
						</td>
						<td data-header="Productos">Kebab Clásico x1, Bebida x1<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">Transferencia</td>
						<td data-header="Incidencias">Falta de confirmación</td>
					</tr>
					<tr data-id="006" data-status="recibido">
						<td data-header="ID Pedido">006</td>
						<td data-header="Fecha">15/12/2024</td>
						<td data-header="Total (€)">18.00</td>
						<td data-header="Estado" class="estado-recibido">
							Recibido
						</td>
						<td data-header="Productos">Kebab Mixto x3<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">Tarjeta</td>
						<td data-header="Incidencias">Pedido incompleto</td>
					</tr>
					<tr data-id="007" data-status="completado">
						<td data-header="ID Pedido">007</td>
						<td data-header="Fecha">15/12/2024</td>
						<td data-header="Total (€)">23.50</td>
						<td data-header="Estado" class="estado-completado">
							Completado
						</td>
						<td data-header="Productos">Kebab Clásico x4<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">Efectivo</td>
						<td data-header="Incidencias">Demora en el procesamiento</td>
					</tr>
					<tr data-id="008" data-status="cancelado">
						<td data-header="ID Pedido">008</td>
						<td data-header="Fecha">16/12/2024</td>
						<td data-header="Total (€)">22.50</td>
						<td data-header="Estado" class="estado-cancelado">
							Cancelado
						</td>
						<td data-header="Productos">Kebab de Pollo x2, Bebida x1<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">Tarjeta</td>
						<td data-header="Incidencias">Problema con la entrega</td>
					</tr>
					<tr data-id="009" data-status="enviado">
						<td data-header="ID Pedido">009</td>
						<td data-header="Fecha">17/12/2024</td>
						<td data-header="Total (€)">21.50</td>
						<td data-header="Estado" class="estado-enviado">
							Enviado
						</td>
						<td data-header="Productos">Kebab de Pollo Picante x1<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">PayPal</td>
						<td data-header="Incidencias">Ninguna</td>
					</tr>
					<tr data-id="010" data-status="completado">
						<td data-header="ID Pedido">010</td>
						<td data-header="Fecha">18/12/2024</td>
						<td data-header="Total (€)">20.00</td>
						<td data-header="Estado" class="estado-completado">
							Completado
						</td>
						<td data-header="Productos">Kebab Clásico x1<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">Transferencia</td>
						<td data-header="Incidencias">Pago aceptado</td>
					</tr>
					</tbody>
				</table>

				<!-- Modal para detalle de pedido -->
				<div id="modalPedido" class="modal">
					<div class="modal-content">
						<span class="close">&times;</span>
						<h2>Resumen del Pedido</h2>
						<p><strong>ID Pedido:</strong> 001</p>
						<p><strong>Fecha:</strong> 01/12/2024</p>
						<h3>Productos:</h3>
						<ul>
							<li>
								<strong>Kebab Pollo:</strong>
								<ul>
									<li>Ingredientes: Pollo, salsa yogur, pepinillos, tomate, lechuga</li>
									<li>Cantidad: 2 unidades</li>
									<li>Precio unidad: 6.3€</li>
								</ul>
							</li>
							<li>
								<strong>Kebab Vegetariano:</strong>
								<ul>
									<li>Ingredientes: Queso, salsa de tomate, espinacas, cebolla caramelizada</li>
									<li>Cantidad: 1 unidad</li>
									<li>Precio unidad: 7.0€</li>
								</ul>
							</li>
							<li>
								<strong>Bebida:</strong>
								<ul>
									<li>Ingredientes: Agua con gas</li>
									<li>Cantidad: 1 unidad</li>
									<li>Precio unidad: 1.5€</li>
								</ul>
							</li>
						</ul>
						<p><strong>Total del pedido:</strong> 45.00€</p>
					</div>
				</div>

			</div>
		</section>
	</div>

<?php $this->stop() ?>

<?php $this->start('scripts'); ?>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var estadoFilter = document.getElementById("estadoFilter");
			var pedidos = document.getElementById("pedidoTable").getElementsByTagName("tr");

			estadoFilter.onchange = function() {
				var selectedStatus = estadoFilter.value.toLowerCase(); // Asegúrate de que el valor sea en minúsculas
				for (var i = 0; i < pedidos.length; i++) {
					var pedidoStatus = pedidos[i].getAttribute("data-status");

					if (selectedStatus === "todos" || pedidoStatus === selectedStatus) {
						pedidos[i].style.display = "";
					} else {
						pedidos[i].style.display = "none";
					}
				}
			}
		});

		// Obtener elementos de la modal y cerrar botón
		var modal = document.getElementById("modalPedido");
		var btns = document.getElementsByClassName("information");
		var span = document.getElementsByClassName("close")[0];

		// Abrir la modal cuando se hace clic en un botón de información
		for (var i = 0; i < btns.length; i++) {
			btns[i].onclick = function() {
				modal.style.display = "flex";
				document.querySelector('body').style.overflow = "hidden";
			}
		}

		// Cerrar la modal cuando se hace clic en la "x"
		span.onclick = function() {
			modal.style.display = "none";
			document.querySelector('body').style.overflow = "auto";
		}

		// Cerrar la modal cuando se hace clic fuera de la modal
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
				document.querySelector('body').style.overflow = "auto";
			}
		}
	</script>

<?php $this->stop(); ?>