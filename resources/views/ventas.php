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

		.wrapper {
			padding: 24px;
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
				<h1>Panel de Ventas</h1>
				<div class="filtros">
					<div class="filtro">
						<label for="periodo-annio">Seleccionar año: </label>
						<select id="periodo-annio" class="input-comun">
							<option value="2024">2024</option>
							<option value="2023">2023</option>
							<option value="2022">2022</option>
							<option value="2021">2021</option>
						</select>
					</div>
					<div class="filtro">
						<label for="periodo-trimestre">Seleccionar periodo: </label>
						<select id="periodo-trimestre" class="input-comun">
							<option value="completo">Completo</option>
							<option value="trimestre1">1º Trimestre</option>
							<option value="trimestre2">2º Trimestre</option>
							<option value="trimestre3">3º Trimestre</option>
						</select>
					</div>
				</div>
			</header>

			<div class="tabla-ventas">
				<table class="table-adapt">
					<thead>
					<tr>
						<th>Mes</th>
						<th>Pedidos Totales</th>
						<th>Ingresos Totales (€)</th>
						<th>Ticket Promedio (€)</th>
						<th>Costes Totales (€)</th>
						<th>Beneficio Neto (€)</th>
						<th>Margen Crítico (€)</th>
						<th>Rentabilidad Neta</th>
						<th>Producto Más Vendido</th>
						<th>Pedidos Devueltos</th>
						<th>Incidencias</th>
					</tr>
					</thead>
					<tbody id="ventas-datos">
					<tr data-month="Enero">
						<td data-header="Mes" style="background-color: #f0f0f0;">Enero</td>
						<td data-header="Pedidos Totales">180</td>
						<td data-header="Ingresos Totales (€)">4,140</td>
						<td data-header="Ticket Promedio (€)">23.0</td>
						<td data-header="Costes Totales (€)">3,200</td>
						<td data-header="Beneficio Neto (€)">940</td>
						<td data-header="Margen Crítico (€)">2,000</td>
						<td data-header="Rentabilidad Neta" style="color: red;">-22%</td>
						<td data-header="Producto Más Vendido">Kebab Clásico</td>
						<td data-header="Pedidos Devueltos">8</td>
						<td data-header="Incidencias">Demandas bajas tras las fiestas navideñas.</td>
					</tr>
					<tr data-month="Febrero">
						<td data-header="Mes" style="background-color: #f0f0f0;">Febrero</td>
						<td data-header="Pedidos Totales">190</td>
						<td data-header="Ingresos Totales (€)">4,370</td>
						<td data-header="Ticket Promedio (€)">23.0</td>
						<td data-header="Costes Totales (€)">3,300</td>
						<td data-header="Beneficio Neto (€)">1,070</td>
						<td data-header="Margen Crítico (€)">2,000</td>
						<td data-header="Rentabilidad Neta" style="color: red;">-23%</td>
						<td data-header="Producto Más Vendido">Kebab Pollo</td>
						<td data-header="Pedidos Devueltos">10</td>
						<td data-header="Incidencias">Demanda estacional por la llegada de turistas</td>
					</tr>
					<tr data-month="Marzo">
						<td data-header="Mes" style="background-color: #f0f0f0;">Marzo</td>
						<td data-header="Pedidos Totales">200</td>
						<td data-header="Ingresos Totales (€)">4,800</td>
						<td data-header="Ticket Promedio (€)">24.0</td>
						<td data-header="Costes Totales (€)">3,500</td>
						<td data-header="Beneficio Neto (€)">1,300</td>
						<td data-header="Margen Crítico (€)">2,100</td>
						<td data-header="Rentabilidad Neta" style="color: green;">+30%</td>
						<td data-header="Producto Más Vendido">Kebab Mixto</td>
						<td data-header="Pedidos Devueltos">5</td>
						<td data-header="Incidencias">Problemas con los envíos</td>
					</tr>
					<tr data-month="Abril">
						<td data-header="Mes" style="background-color: #f0f0f0;">Abril</td>
						<td data-header="Pedidos Totales">210</td>
						<td data-header="Ingresos Totales (€)">5,100</td>
						<td data-header="Ticket Promedio (€)">24.3</td>
						<td data-header="Costes Totales (€)">3,700</td>
						<td data-header="Beneficio Neto (€)">1,400</td>
						<td data-header="Margen Crítico (€)">2,200</td>
						<td data-header="Rentabilidad Neta" style="color: green;">+35%</td>
						<td data-header="Producto Más Vendido">Kebab Vegetal</td>
						<td data-header="Pedidos Devueltos">4</td>
						<td data-header="Incidencias">Problemas de stock en productos clave</td>
					</tr>
					<tr data-month="Mayo">
						<td data-header="Mes" style="background-color: #f0f0f0;">Mayo</td>
						<td data-header="Pedidos Totales">230</td>
						<td data-header="Ingresos Totales (€)">5,400</td>
						<td data-header="Ticket Promedio (€)">23.5</td>
						<td data-header="Costes Totales (€)">3,800</td>
						<td data-header="Beneficio Neto (€)">1,600</td>
						<td data-header="Margen Crítico (€)">2,300</td>
						<td data-header="Rentabilidad Neta" style="color: green;">+40%</td>
						<td data-header="Producto Más Vendido">Kebab de Pollo Picante</td>
						<td data-header="Pedidos Devueltos">6</td>
						<td data-header="Incidencias">Problemas con la calidad del producto</td>
					</tr>
					<tr data-month="Junio">
						<td data-header="Mes" style="background-color: #f0f0f0;">Junio</td>
						<td data-header="Pedidos Totales">240</td>
						<td data-header="Ingresos Totales (€)">5,700</td>
						<td data-header="Ticket Promedio (€)">23.8</td>
						<td data-header="Costes Totales (€)">3,900</td>
						<td data-header="Beneficio Neto (€)">1,800</td>
						<td data-header="Margen Crítico (€)">2,400</td>
						<td data-header="Rentabilidad Neta" style="color: green;">+32%</td>
						<td data-header="Producto Más Vendido">Kebab de Cordero</td>
						<td data-header="Pedidos Devueltos">7</td>
						<td data-header="Incidencias">Incremento en las devoluciones durante festividades</td>
					</tr>
					<tr data-month="Julio">
						<td data-header="Mes" style="background-color: #f0f0f0;">Julio</td>
						<td data-header="Pedidos Totales">250</td>
						<td data-header="Ingresos Totales (€)">6,000</td>
						<td data-header="Ticket Promedio (€)">24.0</td>
						<td data-header="Costes Totales (€)">4,000</td>
						<td data-header="Beneficio Neto (€)">2,000</td>
						<td data-header="Margen Crítico (€)">2,500</td>
						<td data-header="Rentabilidad Neta" style="color: green;">+30%</td>
						<td data-header="Producto Más Vendido">Kebab de Pollo</td>
						<td data-header="Pedidos Devueltos">5</td>
						<td data-header="Incidencias">Problemas de gestión de stock</td>
					</tr>
					<tr data-month="Agosto">
						<td data-header="Mes" style="background-color: #f0f0f0;">Agosto</td>
						<td data-header="Pedidos Totales">260</td>
						<td data-header="Ingresos Totales (€)">6,200</td>
						<td data-header="Ticket Promedio (€)">23.8</td>
						<td data-header="Costes Totales (€)">4,100</td>
						<td data-header="Beneficio Neto (€)">2,100</td>
						<td data-header="Margen Crítico (€)">2,600</td>
						<td data-header="Rentabilidad Neta" style="color: red;">-27%</td>
						<td data-header="Producto Más Vendido">Kebab de Pollo Picante</td>
						<td data-header="Pedidos Devueltos">9</td>
						<td data-header="Incidencias">Problemas de calidad en insumos</td>
					</tr>
					<tr data-month="Septiembre">
						<td data-header="Mes" style="background-color: #f0f0f0;">Septiembre</td>
						<td data-header="Pedidos Totales">270</td>
						<td data-header="Ingresos Totales (€)">6,500</td>
						<td data-header="Ticket Promedio (€)">24.1</td>
						<td data-header="Costes Totales (€)">4,200</td>
						<td data-header="Beneficio Neto (€)">2,300</td>
						<td data-header="Margen Crítico (€)">2,700</td>
						<td data-header="Rentabilidad Neta" style="color: green;">+36%</td>
						<td data-header="Producto Más Vendido">Kebab Clásico</td>
						<td data-header="Pedidos Devueltos">6</td>
						<td data-header="Incidencias">Problemas en la logística de envíos</td>
					</tr>
					<tr data-month="Octubre">
						<td data-header="Mes" style="background-color: #f0f0f0;">Octubre</td>
						<td data-header="Pedidos Totales">280</td>
						<td data-header="Ingresos Totales (€)">6,800</td>
						<td data-header="Ticket Promedio (€)">24.5</td>
						<td data-header="Costes Totales (€)">4,300</td>
						<td data-header="Beneficio Neto (€)">2,500</td>
						<td data-header="Margen Crítico (€)">2,800</td>
						<td data-header="Rentabilidad Neta" style="color: green;">+38%</td>
						<td data-header="Producto Más Vendido">Kebab Vegetal</td>
						<td data-header="Pedidos Devueltos">7</td>
						<td data-header="Incidencias">Demanda estacional alta</td>
					</tr>
					<tr data-month="Noviembre">
						<td data-header="Mes" style="background-color: #f0f0f0;">Noviembre</td>
						<td data-header="Pedidos Totales">300</td>
						<td data-header="Ingresos Totales (€)">7,000</td>
						<td data-header="Ticket Promedio (€)">24.3</td>
						<td data-header="Costes Totales (€)">4,500</td>
						<td data-header="Beneficio Neto (€)">2,500</td>
						<td data-header="Margen Crítico (€)">2,900</td>
						<td data-header="Rentabilidad Neta" style="color: green;">+40%</td>
						<td data-header="Producto Más Vendido">Kebab de Cordero</td>
						<td data-header="Pedidos Devueltos">8</td>
						<td data-header="Incidencias">Problemas con la demanda navideña</td>
					</tr>
					<tr data-month="Diciembre">
						<td data-header="Mes" style="background-color: #f0f0f0;">Diciembre</td>
						<td data-header="Pedidos Totales">320</td>
						<td data-header="Ingresos Totales (€)">7,500</td>
						<td data-header="Ticket Promedio (€)">23.5</td>
						<td data-header="Costes Totales (€)">4,800</td>
						<td data-header="Beneficio Neto (€)">2,700</td>
						<td data-header="Margen Crítico (€)">3,000</td>
						<td data-header="Rentabilidad Neta" style="color: green;">+42%</td>
						<td data-header="Producto Más Vendido">Kebab Clásico</td>
						<td data-header="Pedidos Devueltos">9</td>
						<td data-header="Incidencias">Alta incidencia de devoluciones</td>
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