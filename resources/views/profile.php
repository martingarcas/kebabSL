<?php use App\Utils\Logger;

$this->layout('master'); ?>
<?php $this->start('css'); ?>
<link rel="stylesheet" href="stylesCarrito.css">
<style>
	.card-row {
		text-align: center;
		margin: 20px 25px 10px; }

	.card-row span {
		width: 48px;
		height: 30px;
		margin-right: 3px;
		background-repeat: no-repeat;
		display: inline-block;
		background-size: contain; }

	.card-image {
		background-repeat: no-repeat;
		padding-right: 50px;
		background-position: right 2px center;
		background-size: auto 90%; }

	.cvc-preview-container {
		overflow: hidden; }

	.cvc-preview-container.two-card div {
		width: 48%;
		height: 80px; }

	.cvc-preview-container.two-card div.visa-mc-dis-cvc-preview {
		float: left; }

	.cvc-preview-container div {
		height: 160px; }

	.submit-button-lock {
		height: 20px;
		margin-top: -2px;
		margin-right: 7px;
		vertical-align: middle;
		background: url("data:image/png;base64,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NQxdFfpzGC4sNKwm7Hm4VXh+eHcELWJFREPEu0iPyNLIR4uNFksWd0bJR8VF1UdNRXtFl0VLl1gsWbPkRoxajCCmPRYfGxV7JHZyqffS3UuH4+ziCuPuLjNclrPs2nK15anLz66QX8FZcSoeGx8d3xD/iRPCqeVMrvRfuXflBNeTu4f7kufGK+eN8V34ZfyRBJeEsoTRRJfEXYljSa5JFUnjAk9BteB1sl/ygeSplJCUoykzqdGpzWmEtPi000IlYYqwK10zPSe9L8M0ozBDuspp1e5VE6JA0ZFMKHNZZruYjv5M9UiMJJslg1kLs2qy3mdHZZ/KUcwR5vTkmuRuyx3J88n7fjVmNXd1Z752/ob8wTXuaw6thdauXNu5Tnddwbrh9b7rj20gbUjZ8MtGy41lG99uit7UUaBRsL5gaLPv5sZCuUJR4b0tzlsObMVsFWzt3WazrWrblyJe0fViy+KK4k8l3JLr31l9V/ndzPaE7b2l9qX7d+B2CHfc3em681iZYlle2dCu4F2t5czyovK3u1fsvlZhW3FgD2mPZI+0MqiyvUqvakfVp+qk6oEaj5rmvep7t+2d2sfb17/fbX/TAY0DxQc+HhQcvH/I91BrrUFtxWHc4azDz+ui6rq/Z39ff0TtSPGRz0eFR6XHwo911TvU1zeoN5Q2wo2SxrHjccdv/eD1Q3sTq+lQM6O5+AQ4ITnx4sf4H++eDDzZeYp9qukn/Z/2ttBailqh1tzWibakNml7THvf6YDTnR3OHS0/m/989Iz2mZqzymdLz5HOFZybOZ93fvJCxoXxi4kXhzpXdD66tOTSna6wrt7LgZevXvG5cqnbvfv8VZerZ645XTt9nX297Yb9jdYeu56WX+x+aem172296XCz/ZbjrY6+BX3n+l37L972un3ljv+dGwOLBvruLr57/17cPel93v3RB6kPXj/Mejj9aP1j7OOiJwpPKp6qP6391fjXZqm99Oyg12DPs4hnj4a4Qy//lfmvT8MFz6nPK0a0RupHrUfPjPmM3Xqx9MXwy4yX0+OFvyn+tveV0auffnf7vWdiycTwa9HrmT9K3qi+OfrW9m3nZOjk03dp76anit6rvj/2gf2h+2P0x5Hp7E/4T5WfjT93fAn88ngmbWbm3/eE8/syOll+AAAAYFBMVEUAAAD///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////98JRy6AAAAH3RSTlMAAgYMEyIzOUpTVFViY3N2gJmcnaipq7fX3ebx+Pn8eTEuDQAAAI9JREFUKM/N0UkOglAQRdFHDyK90n64+9+lAyQgookjuaNKTlJJpaQlO2n6sW8SW/uCjrku2EloWDLhi3gDa4O3pTtA5Tt+BXDbiDsBmSQpAyZ3pRhoLUmS1QLxSilQPOcCSFfKgfxgPgfZ9ch7Y21LCcdd5wVH5SckEzkXc0ylpPJnMpETmX/d9eUpH1/5AKrsQVrz7YPBAAAAAElFTkSuQmCC") center center/contain no-repeat;
		width: 14px;
		display: inline-block; }

	.align-middle {
		vertical-align: middle; }

	input {
		box-shadow: none !important; }

	input:focus {
		border-color: #b0e5e3 !important;
		background-color: #EEF9F9 !important; }


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

<div class="ccc-modal-overlay"></div>

<div class="profile-wrapper">

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

<script>

	document.addEventListener('DOMContentLoaded', function () {


		let pagoModal = document.querySelector('.container');
		var span = document.getElementsByClassName("close")[0];
		let totalPayments = document.querySelector('.guardar-monto');
		// Cerrar la modal cuando se hace clic en la "x"
		span.onclick = function() {
			pagoModal.style.display = "none";
			document.querySelector('body').style.overflow = "auto";
			document.querySelector('.ccc-modal-overlay').style.display = 'none';
		}

		function openModalPayment() {
			document.querySelector('body').style.overflow = 'hidden';
			document.querySelector('.ccc-modal-overlay').style.display = 'block';
			pagoModal.style.display = "block";
		}

		document.querySelector('#guardar-monto').addEventListener('click', function (e) {
			e.preventDefault();
			openModalPayment();
		});
	});
</script>

<?php $this->stop() ?>
