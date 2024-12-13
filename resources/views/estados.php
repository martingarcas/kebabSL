<?php $this->layout('master'); ?>
<?php $this->start('css'); ?>
	<link rel="stylesheet" href="stylesRegister.css">
	<link rel="stylesheet" href="stylesCarrito.css">

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

		/* Estilo para la sección principal */
		.estadisticas {
			max-width: 1200px;
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
			margin-top: 10px;
		}

		.filtros label {
			font-size: 1rem;
			margin-right: 10px;
		}

		.filtros select {
			padding: 8px;
			font-size: 1rem;
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
		}

		th, td {
			padding: 10px;
			border: 1px solid #ddd;
		}

		th {
			background-color: #f2f2f2;
			font-weight: bold;
		}

		tbody tr:nth-child(even) {
			background-color: #fafafa;
		}

		/* Estilos responsivos */
		@media (max-width: 768px) {
			table, th, td {
				font-size: 0.9rem;
			}

			.filtros {
				flex-direction: column;
				align-items: center;
			}

			.filtros label {
				margin-bottom: 5px;
			}
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


	<section class="estadisticas">
		<header>
			<h1>Panel de Ventas</h1>
			<div class="filtros">
				<label for="periodo">Seleccionar periodo: </label>
				<select id="periodo">
					<option value="2024">2024</option>
					<option value="2023">2023</option>
					<option value="2022">2022</option>
				</select>
			</div>
		</header>

		<div class="tabla-ventas">
			<table>
				<thead>
				<tr>
					<th>Mes</th>
					<th>Ventas Totales</th>
					<th>Producto Más Vendido</th>
					<th>Total por Producto</th>
					<th>Diferencia con el Mes Anterior</th>
					<th>Beneficios/Pérdidas</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>Enero</td>
					<td>250</td>
					<td>Kebab Campeón</td>
					<td>150</td>
					<td>-</td> <!-- Primer mes, no hay comparación -->
					<td>$500</td> <!-- Suponiendo $2 de beneficio por venta -->
				</tr>
				<tr>
					<td>Febrero</td>
					<td>200</td>
					<td>Kebab Aarab</td>
					<td>120</td>
					<td>-50</td> <!-- Diferencia con Enero -->
					<td>$400</td>
				</tr>
				<tr>
					<td>Marzo</td>
					<td>280</td>
					<td>Custom</td>
					<td>160</td>
					<td>+80</td> <!-- Diferencia con Febrero -->
					<td>$560</td>
				</tr>
				<tr>
					<td>Abril</td>
					<td>230</td>
					<td>Kebab Campeón</td>
					<td>140</td>
					<td>-50</td> <!-- Diferencia con Marzo -->
					<td>$460</td>
				</tr>
				<tr>
					<td>Mayo</td>
					<td>310</td>
					<td>Kebab Aarab</td>
					<td>180</td>
					<td>+80</td> <!-- Diferencia con Abril -->
					<td>$620</td>
				</tr>
				<tr>
					<td>Junio</td>
					<td>290</td>
					<td>Custom</td>
					<td>170</td>
					<td>-20</td> <!-- Diferencia con Mayo -->
					<td>$580</td>
				</tr>
				<tr>
					<td>Julio</td>
					<td>350</td>
					<td>Kebab Campeón</td>
					<td>200</td>
					<td>+60</td> <!-- Diferencia con Junio -->
					<td>$700</td>
				</tr>
				<tr>
					<td>Agosto</td>
					<td>240</td>
					<td>Kebab Aarab</td>
					<td>130</td>
					<td>-110</td> <!-- Diferencia con Julio -->
					<td>$480</td>
				</tr>
				<tr>
					<td>Septiembre</td>
					<td>280</td>
					<td>Custom</td>
					<td>160</td>
					<td>+40</td> <!-- Diferencia con Agosto -->
					<td>$560</td>
				</tr>
				<tr>
					<td>Octubre</td>
					<td>300</td>
					<td>Kebab Campeón</td>
					<td>170</td>
					<td>+20</td> <!-- Diferencia con Septiembre -->
					<td>$600</td>
				</tr>
				<tr>
					<td>Noviembre</td>
					<td>320</td>
					<td>Kebab Aarab</td>
					<td>180</td>
					<td>+20</td> <!-- Diferencia con Octubre -->
					<td>$640</td>
				</tr>
				<tr>
					<td>Diciembre</td>
					<td>370</td>
					<td>Custom</td>
					<td>200</td>
					<td>+50</td> <!-- Diferencia con Noviembre -->
					<td>$740</td>
				</tr>
				</tbody>
			</table>
		</div>
	</section>



<?php $this->stop() ?>

<?php $this->start('scripts'); ?>

<?php $this->stop(); ?>