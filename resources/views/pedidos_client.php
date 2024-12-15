<?php $this->layout('master'); ?>
<?php $this->start('css'); ?>
<!--	<style>-->
<!--		/* Reset básico de estilos */-->
<!--		* {-->
<!--			margin: 0;-->
<!--			padding: 0;-->
<!--			box-sizing: border-box;-->
<!--		}-->
<!---->
<!--		body {-->
<!--			font-family: Arial, sans-serif;-->
<!--			background-color: #f4f4f4;-->
<!--			color: #333;-->
<!--			line-height: 1.6;-->
<!--		}-->
<!---->
<!--		.wrapper {-->
<!--			padding: 24px;-->
<!--		}-->
<!---->
<!--		/* Estilo para la sección principal */-->
<!--		.estadisticas {-->
<!--			margin: 20px auto;-->
<!--			padding: 20px;-->
<!--			background-color: #fff;-->
<!--			border-radius: 8px;-->
<!--			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);-->
<!--		}-->
<!---->
<!--		/* Estilos del encabezado */-->
<!--		header {-->
<!--			text-align: center;-->
<!--			margin-bottom: 20px;-->
<!--		}-->
<!---->
<!--		header h1 {-->
<!--			font-size: 2rem;-->
<!--			color: #333;-->
<!--		}-->
<!---->
<!--		.filtros {-->
<!--			display: flex;-->
<!--			justify-content: center;-->
<!--			gap: 42px;-->
<!--			margin-top: 10px;-->
<!--		}-->
<!---->
<!--		.filtro {-->
<!--			display: flex;-->
<!--			gap: 4px;-->
<!--			align-items: baseline;-->
<!--		}-->
<!---->
<!--		.filtros label {-->
<!--			font-size: 1rem;-->
<!--		}-->
<!---->
<!--		.filtros select {-->
<!--			padding: 8px;-->
<!--			border: 1px solid #ddd;-->
<!--			border-radius: 4px;-->
<!--		}-->
<!---->
<!--		/* Estilos de la tabla */-->
<!--		.tabla-ventas {-->
<!--			overflow-x: auto;-->
<!--		}-->
<!---->
<!--		table {-->
<!--			width: 100%;-->
<!--			border-collapse: collapse;-->
<!--			text-align: center;-->
<!--			transition: font-size .5s;-->
<!--		}-->
<!---->
<!--		th, td {-->
<!--			padding: 10px;-->
<!--			border: 1px solid #ddd;-->
<!--			font-size: 0.9rem; /* Tamaño de fuente más pequeño para mayor claridad */-->
<!--		}-->
<!---->
<!--		th {-->
<!--			background-color: #007BFF; /* Azul para los encabezados */-->
<!--			color: #fff;-->
<!--			font-weight: bold;-->
<!--		}-->
<!---->
<!--		tbody tr:nth-child(even) {-->
<!--			background-color: #fafafa;-->
<!--		}-->
<!---->
<!--		/* Resaltar filas según rentabilidad */-->
<!--		td[style*="color: red"] {-->
<!--			background-color: #ffe6e6; /* Rojo claro */-->
<!--			font-weight: bold;-->
<!--			color: #e60000; /* Color rojo para el texto */-->
<!--		}-->
<!---->
<!--		td[style*="color: green"] {-->
<!--			background-color: #e6ffe6; /* Verde claro */-->
<!--			font-weight: bold;-->
<!--			color: #008000; /* Color verde para el texto */-->
<!--		}-->
<!---->
<!--		select:focus {-->
<!--			outline: none;-->
<!--			border: 2px solid #007BFF; /* Color azul para indicar enfoque */-->
<!--			box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);-->
<!--		}-->
<!---->
<!--		/* Estilos responsivos */-->
<!--		@media (max-width: 600px) {-->
<!--			table, th, td {-->
<!--				font-size: 0.8rem; /* Tamaño de fuente más pequeño en dispositivos más pequeños */-->
<!--			}-->
<!---->
<!--			.filtros {-->
<!--				flex-direction: column;-->
<!--				align-items: center;-->
<!--				gap: 24px;-->
<!--			}-->
<!---->
<!--			.filtros label {-->
<!--				margin-bottom: 5px;-->
<!--			}-->
<!---->
<!--			/* Adaptación de la tabla en pantallas pequeñas */-->
<!--			.table-adapt {-->
<!--				display: block;-->
<!--				overflow-x: auto; /* Para que la tabla se pueda desplazar horizontalmente */-->
<!--				margin-top: 20px;-->
<!--			}-->
<!---->
<!--			.table-adapt thead, .table-adapt tbody {-->
<!--				display: block;-->
<!--			}-->
<!---->
<!--			thead tr {-->
<!--				display: none !important;-->
<!--			}-->
<!---->
<!--			tr {-->
<!--				display: flex;-->
<!--				flex-direction: column;-->
<!--				border-bottom: 1px solid #eee !important;-->
<!--				margin-bottom: 10px;-->
<!--				padding-bottom: 5px;-->
<!--			}-->
<!---->
<!--			td {-->
<!--				padding-left: 10%;-->
<!--				border: none;-->
<!--				position: relative;-->
<!--				width: 100%;-->
<!--				display: flex;-->
<!--				justify-content: space-between; /* Alinea el encabezado y el dato uno al lado del otro */-->
<!--				align-items: center;-->
<!--				margin-bottom: 10px; /* Añade espacio entre cada par de dato */-->
<!--			}-->
<!---->
<!--			td:before {-->
<!--				flex-basis: 35%; /* Asegura que la cabecera ocupe una mayor parte de la línea */-->
<!--				color: #999;-->
<!--				font-weight: bold;-->
<!--				content: attr(data-header);-->
<!--				margin-right: 10px; /* Añade espacio entre el encabezado y el dato */-->
<!--			}-->
<!---->
<!--			/* Estilos adicionales para los encabezados */-->
<!--			th {-->
<!--				display: none;-->
<!--			}-->
<!---->
<!--			/* Extra */-->
<!--			small { display: block; }-->
<!--		}-->
<!--	</style>-->
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

		.wrapper {
			padding: 24px;
		}

		/* Estilos para los estados de los pedidos */
		.estado-recibido {
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
						<label for="filtro-estado">Filtrar por estado:</label>
						<select id="filtro-estado" class="input-comun">
							<option value="todos">Todos</option>
							<option value="pendiente">Recibido</option>
							<option value="preparando">En preparación</option>
							<option value="enviado">Enviado</option>
							<option value="entregado">Completado</option>
							<option value="cancelado">Cancelado</option>
						</select>
					</div>
					<div class="filtro">
						<label for="filtro-fecha">Seleccionar periodo:</label>
						<select id="filtro-fecha" class="input-comun">
							<option value="hoy">Hoy</option>
							<option value="semana">Última semana</option>
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
						<th>Cliente</th>
						<th>Fecha</th>
						<th>Total (€)</th>
						<th>Estado</th>
						<th>Productos</th>
						<th>Método de Pago</th>
						<th>Incidencias</th>
					</tr>
					</thead>
					<tbody id="pedidos-datos">
					<tr data-id="001">
						<td data-header="ID Pedido">001</td>
						<td data-header="Cliente">Juan Pérez</td>
						<td data-header="Fecha">2024-12-10</td>
						<td data-header="Total (€)">45.00</td>
						<td data-header="Estado" class="estado-recibido">
							Recibido
						</td>
						<td data-header="Productos">Kebab Clásico x2<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">Tarjeta</td>
						<td data-header="Incidencias">Ninguna</td>
					</tr>
					<tr data-id="002">
						<td data-header="ID Pedido">002</td>
						<td data-header="Cliente">Ana Gómez</td>
						<td data-header="Fecha">2024-12-11</td>
						<td data-header="Total (€)">30.50</td>
						<td data-header="Estado" class="estado-preparando">
							En preparación
						</td>
						<td data-header="Productos">Kebab Vegetal x1, Bebida x1<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">PayPal</td>
						<td data-header="Incidencias">Demora en el envío</td>
					</tr>
					<tr data-id="003">
						<td data-header="ID Pedido">003</td>
						<td data-header="Cliente">Carlos Ruiz</td>
						<td data-header="Fecha">2024-12-12</td>
						<td data-header="Total (€)">25.00</td>
						<td data-header="Estado" class="estado-enviado">
							Enviado
						</td>
						<td data-header="Productos">Kebab de Pollo Picante x1<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">Efectivo</td>
						<td data-header="Incidencias">Pedido mal ingresado</td>
					</tr>
					<tr data-id="004">
						<td data-header="ID Pedido">004</td>
						<td data-header="Cliente">Lucía Fernández</td>
						<td data-header="Fecha">2024-12-13</td>
						<td data-header="Total (€)">50.00</td>
						<td data-header="Estado" class="estado-completado">
							Completado
						</td>
						<td data-header="Productos">Kebab Mixto x2, Postre x1<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">Tarjeta</td>
						<td data-header="Incidencias">Pago aceptado</td>
					</tr>
					<tr data-id="005">
						<td data-header="ID Pedido">005</td>
						<td data-header="Cliente">María López</td>
						<td data-header="Fecha">2024-12-14</td>
						<td data-header="Total (€)">35.00</td>
						<td data-header="Estado" class="estado-pendiente">
							Pendiente
						</td>
						<td data-header="Productos">Kebab Clásico x1, Bebida x1<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">Transferencia</td>
						<td data-header="Incidencias">Falta de confirmación</td>
					</tr>
					<tr data-id="006">
						<td data-header="ID Pedido">006</td>
						<td data-header="Cliente">Luis García</td>
						<td data-header="Fecha">2024-12-15</td>
						<td data-header="Total (€)">18.00</td>
						<td data-header="Estado" class="estado-recibido">
							Recibido
						</td>
						<td data-header="Productos">Kebab Mixto x3<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">Tarjeta</td>
						<td data-header="Incidencias">Pedido incompleto</td>
					</tr>
					<tr data-id="007">
						<td data-header="ID Pedido">007</td>
						<td data-header="Cliente">María Ramírez</td>
						<td data-header="Fecha">2024-12-15</td>
						<td data-header="Total (€)">23.50</td>
						<td data-header="Estado" class="estado-completado">
							Completado
						</td>
						<td data-header="Productos">Kebab Clásico x4<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">Efectivo</td>
						<td data-header="Incidencias">Demora en el procesamiento</td>
					</tr>
					<tr data-id="008">
						<td data-header="ID Pedido">008</td>
						<td data-header="Cliente">Pedro Sánchez</td>
						<td data-header="Fecha">2024-12-16</td>
						<td data-header="Total (€)">22.50</td>
						<td data-header="Estado" class="estado-preparando">
							En preparación
						</td>
						<td data-header="Productos">Kebab de Pollo x2, Bebida x1<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">Tarjeta</td>
						<td data-header="Incidencias">Problema con la entrega</td>
					</tr>
					<tr data-id="009">
						<td data-header="ID Pedido">009</td>
						<td data-header="Cliente">Elena Martín</td>
						<td data-header="Fecha">2024-12-17</td>
						<td data-header="Total (€)">21.50</td>
						<td data-header="Estado" class="estado-enviado">
							Enviado
						</td>
						<td data-header="Productos">Kebab de Pollo Picante x1<span class="information">&#9432;</span></td>
						<td data-header="Método de Pago">PayPal</td>
						<td data-header="Incidencias">Ninguna</td>
					</tr>
					<tr data-id="010">
						<td data-header="ID Pedido">010</td>
						<td data-header="Cliente">Carlos Torres</td>
						<td data-header="Fecha">2024-12-18</td>
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
			</div>
		</section>
	</div>

<?php $this->stop() ?>

<?php $this->start('scripts'); ?>

	<script>

		document.addEventListener('DOMContentLoaded', function() {
			const trimestreSelect = document.getElementById('periodo-trimestre');
			const ventasDatos = document.getElementById('ventas-datos');

			trimestreSelect.addEventListener('change', function() {
				const selectedValue = trimestreSelect.value;

				// Filtrar los datos según el trimestre seleccionado
				switch (selectedValue) {
					case 'completo':
						showAllMonths();
						break;
					case 'trimestre1':
						filterMonths(['Enero', 'Febrero', 'Marzo', 'Abril']);
						break;
					case 'trimestre2':
						filterMonths(['Mayo', 'Junio', 'Julio', 'Agosto']);
						break;
					case 'trimestre3':
						filterMonths(['Septiembre', 'Octubre', 'Noviembre', 'Diciembre']);
						break;
				}
			});

			function showAllMonths() {
				const rows = ventasDatos.getElementsByTagName('tr');
				for (let row of rows) {
					row.style.display = '';
				}
			}

			function filterMonths(months) {
				const rows = ventasDatos.getElementsByTagName('tr');
				for (let row of rows) {
					const month = row.getAttribute('data-month');
					if (months.includes(month)) {
						row.style.display = '';
					} else {
						row.style.display = 'none';
					}
				}
			}
		});

	</script>

<?php $this->stop(); ?>