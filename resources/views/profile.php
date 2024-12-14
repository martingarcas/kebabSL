<?php use App\Utils\Logger;

$this->layout('master'); ?>
<?php $this->start('css'); ?>

<style>

	.profile-wrapper {
		padding: 50px;
	}
	.profile-card {
		max-width: 960px;
		margin: 0 auto;
		background: #fff;
		padding: 20px;
		border: 2px solid #ddd;
		border-radius: 8px;
		background-color: #fafafa;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
		font-family: 'Arial', sans-serif;
	}
	.profile-card .profile-header {
		display: flex;
		gap: 40px;
		margin-bottom: 20px;
		padding-bottom: 70px;
		border-bottom: 1px solid #ddd;
	}
	.profile-card .profile-header .profile-photo {
		flex: 1 1 100px;
		display: flex;
		justify-content: center;
		height: 300px;
		position: relative;
		margin-bottom: 40px;
	}
	#foto-preview {
		max-width: inherit;
		object-fit: contain;
		cursor: inherit;
	}
	.profile-card .profile-header .profile-photo input[type="file"] {
		display: none;
	}
	.profile-card .profile-header .profile-photo img {
		max-width: 100px;
		border-radius: 50%;
		cursor: pointer;
		transition: transform 0.3s ease;
	}
	.profile-card .profile-header .profile-photo img:hover {
		transform: scale(1.1);
	}
	.profile-card .profile-header .profile-photo button {
		position: absolute;
		top: 110%;
		left: 35%;
		max-width: 160px;
		padding: 8px 12px;
		background-color: #007bff;
		color: #fff;
		border: none;
		border-radius: 5px;
		font-size: 0.9rem;
		cursor: pointer;
		transition: all 0.3s ease;
	}
	.profile-card .profile-header .profile-photo button:hover {
		background-color: #0056b3;
	}
	.profile-card .profile-header .profile-details {
		flex: 2;
		display: flex;
		justify-content: center;
		flex-wrap: wrap;
		gap: 40px;
	}
	.profile-card .profile-header .profile-details .form-field {
		margin-bottom: 15px;
	}
	.profile-card .profile-header .profile-details .form-field label {
		display: block;
		font-size: 0.9rem;
		margin-bottom: 5px;
	}
	.profile-card .profile-header .profile-details .form-field input {
		width: 100%;
		padding: 10px;
		border: 1px solid #ddd;
		border-radius: 5px;
		font-size: 1rem;
	}
	.profile-card .profile-sections {
		display: flex;
		flex-wrap: wrap;
		justify-content: space-around;
		/*gap: 20px;*/
	}
	.profile-card .profile-sections .section {
		background: #f9f9f9;
		padding: 15px;
		border-radius: 8px;
		text-align: center;
	}
	.profile-card .profile-sections .section h3 {
		margin-bottom: 10px;
		font-size: 1rem;
		color: #333;
	}
	.profile-card .profile-sections .section textarea, .profile-card .profile-sections .section input {
		/*width: 100%;*/
		padding: 10px;
		border: 1px solid #ddd;
		border-radius: 5px;
		font-size: 1rem;
		margin-bottom: 10px;
	}
	.profile-card .profile-sections .section button {
		padding: 10px;
		background-color: #007bff;
		color: #fff;
		border: none;
		border-radius: 5px;
		cursor: pointer;
		font-size: 1rem;
	}
	.profile-card .profile-sections .section button:hover {
		background-color: #0056b3;
	}
	.profile-card .profile-footer {
		text-align: center;
		margin-top: 20px;
	}
	.profile-card .profile-footer button {
		padding: 12px 20px;
		background-color: #007bff;
		color: #fff;
		border: none;
		border-radius: 5px;
		font-size: 1rem;
		cursor: pointer;
		transition: all 0.3s ease;
	}
	.profile-card .profile-footer button:hover {
		background-color: #0056b3;
	}

	.monto {
		display: flex;
		flex-direction: column;
		justify-content: center;
		/*flex-wrap: wrap;*/
		/*align-items: baseline;*/
		gap: 12px;
	}

	.direccion-fija {
		width: 320px;
	}

	@media (max-width: 860px) {
		.profile-card .profile-header .profile-photo button {
			left: 30%
		}
	}

	@media (max-width: 700px) {
		.profile-card .profile-header {
			flex-direction: column;
		}
		.profile-card .profile-header .profile-photo button {
			left: 39%
		}
	}

	@media (max-width: 560px) {
		.profile-card .profile-header {
			flex-direction: column;
		}
		.profile-card .profile-header .profile-photo button {
			left: 37%
		}
	}

	@media (max-width: 480px) {
		.profile-card .profile-header .profile-photo button {
			left: 32%
		}
		.profile-card .profile-header {
			padding-bottom: 50px;
		}
		.direccion-fija {
			width: initial;
		}
	}

	@media (max-width: 400px) {
		.profile-card .profile-header .profile-photo button {
			left: 25%
		}
	}

	@media (max-width: 340px) {
		.profile-card .profile-header .profile-photo button {
			left: 20%
		}
	}

</style>

<?php $this->stop() ?>

<?php $this->start('formulario') ?>
<div class="profile-wrapper">
	<!-- Aquí se mostrarán los datos cargados -->
	<div class="profile-card">
		<!-- Foto y datos principales -->
		<div class="profile-header">
			<div class="profile-photo">
				<img src="/img/perfil/IMG-20191222-WA0022-2.jpg" alt="Agregar imagen" id="foto-preview">
				<button id="cambiar-foto">Cambiar Foto</button>
			</div>
			<div class="profile-details">
				<div class="group-fields">
					<div class="form-field">
						<label for="nombre">Nombre:</label>
						<input type="text" id="nombre" placeholder="Nombre" value="Martín">
					</div>
					<div class="form-field">
						<label for="apellido1">Primer Apellido:</label>
						<input type="text" id="apellido1" placeholder="Apellido" value="García">
					</div>
					<div class="form-field">
						<label for="apellido2">Segundo Apellido:</label>
						<input type="text" id="apellido2" placeholder="Segundo Apellido" value="Castillo">
					</div>
				</div>
				<div class="group-fields">
					<div class="form-field">
						<label for="dni">DNI:</label>
						<input type="text" id="dni" placeholder="DNI" value="77383320g">
					</div>
					<div class="form-field">
						<label for="email">Email:</label>
						<input type="email" id="email" placeholder="Correo electrónico" value="martin@gmail.com">
					</div>
					<div class="form-field">
						<label for="telefono">Teléfono:</label>
						<input type="text" id="telefono" placeholder="Teléfono" value="693209523">
					</div>
				</div>
			</div>
		</div>

		<!-- Apartados secundarios -->
		<div class="profile-sections">
			<!-- Dirección -->
			<div class="section">
				<h3>Dirección</h3>
				<div class="direccion-list">
					<div class="direccion-item">
						<input type="text" class="direccion-fija" id="direccion-1" placeholder="Dirección" value="Infanta Pilar, nº 21 - Jaén">
						<input type="checkbox" id="direccion-activa-1" class="direccion-activa" checked>
					</div>
					<div class="direccion-item">
						<input type="text" class="direccion-fija" id="direccion-2" placeholder="Dirección" value="Avenida Ruiz Jiménez, nº 2, 3º.B - Jaén">
						<input type="checkbox" id="direccion-activa-2" class="direccion-activa">
					</div>
					<div class="direccion-item">
						<input type="text" class="direccion-fija" id="direccion-2" placeholder="Dirección">
						<input type="checkbox" id="direccion-activa-2" class="direccion-activa">
					</div>
				</div>
				<button id="agregar-direccion">Agregar Nueva Dirección</button>
			</div>
			<!-- Monedero -->
			<div class="section">
				<h3>Monedero</h3>
				<p>Saldo actual: <span id="saldo-actual">50.00</span>€</p>
				<div class="monto">
					<label for="monto-agregar">Agregar dinero:</label>
					<input type="number" id="monto-agregar" placeholder="Introduce la cantidad" min="0">
					<button id="guardar-monto">Guardar</button>
				</div>
			</div>
		</div>

		<!-- Botón para guardar -->
		<div class="profile-footer">
			<button id="guardar-perfil">Guardar Perfil</button>
		</div>
	</div>

</div>


<?php $this->stop() ?>

<?php $this->start('scripts'); ?>

<?php $this->stop() ?>
