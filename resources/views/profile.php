<?php $this->layout('master'); ?>
<?php $this->start('css'); ?>

	<link rel="stylesheet" href="stylesProfile.css">
<?php $this->stop() ?>

<?php $this->start('header') ?>
<h1>Perfil de Usuario</h1>
<?php $this->stop() ?>

<?php $this->start('formulario') ?>

<div class="profile-wrapper">
	<div class="profile-container">
		<h2>Datos del Usuario</h2>
		<div id="profile-data">
			<!-- Aquí se mostrarán los datos cargados -->
		</div>
		<div id="error-message" style="color: red; display: none;">
			<!-- Aquí se mostrarán los errores -->
		</div>
		<div class="spinner" id="spinner"></div>
	</div>
</div>

<?php $this->stop() ?>

<?php $this->start('scripts'); ?>

<script>
	document.addEventListener('DOMContentLoaded', () => {
		const profileContainer = document.getElementById('profile-data');
		const errorMessage = document.getElementById('error-message');
		const spinner = document.getElementById('spinner');

		// Función para mostrar el spinner
		function showSpinner() {
			spinner.style.display = 'block';
			profileContainer.innerHTML = '';
			errorMessage.style.display = 'none';
		}

		// Función para ocultar el spinner
		function hideSpinner() {
			spinner.style.display = 'none';
		}

		// Realizar una petición para obtener los datos del usuario
		async function fetchUserData() {
			showSpinner();
			try {
				const formData = new FormData();
				formData.append('action', 'loadUser');

				const response = await fetch('/apiUser', {
					method: 'POST',
					body: formData
				});

				const data = await response.json();

				if (response.ok && data.success) {
					renderUserProfile(data.usuario);
				} else {
					showError(data.error || 'Error desconocido.');
				}
			} catch (error) {
				showError('Error en la conexión con el servidor.');
			} finally {
				hideSpinner();
			}
		}

		// Renderizar los datos del usuario en la página
		function renderUserProfile(user) {
			errorMessage.style.display = 'none';
			profileContainer.innerHTML = `
				<p><strong>Nombre:</strong> ${user.nombre}</p>
				<p><strong>Primer Apellido:</strong> ${user.apellido1 || '-'}</p>
				<p><strong>Segundo Apellido:</strong> ${user.apellido2 || '-'}</p>
				<p><strong>Email:</strong> ${user.email}</p>
				<p><strong>Teléfono:</strong> ${user.telefono || '-'}</p>
				<p><strong>Foto:</strong> ${user.foto || '-'}</p>
				<p><strong>DNI:</strong> ${user.dni}</p>
				<p><strong>Monedero:</strong> ${user.monedero || '0'}€</p>
			`;
		}

		// Mostrar errores en la página
		function showError(message) {
			profileContainer.innerHTML = '';
			errorMessage.style.display = 'block';
			errorMessage.textContent = message;
		}

		fetchUserData();
	});
</script>

<?php $this->stop() ?>
