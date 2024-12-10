<?php $this->layout('master'); ?>

<?php $this->start('css'); ?>
<link rel="stylesheet" href="stylesRegister.css">
<?php $this->stop() ?>

<?php $this->start('header') ?>
<?php $this->stop() ?>

<?php $this->start('formulario') ?>
<!-- Mostrar el mensaje flash, si existe -->
<?php if (isset($message)): ?>
	<div class="flash-message <?= htmlspecialchars($message['type']) ?>" id="flashMessage">
		<?= htmlspecialchars($message['message']) ?>
	</div>
<?php endif; ?>

<div class="form-wrapper">
	<div class="form-container form-login">
		<form action="/login-post" method="POST" class="formulario__login">
			<!-- Datos de Inicio de Sesión -->
			<fieldset>
				<legend>Inicio de Sesión</legend>

				<label for="email">Correo Electrónico:</label>
				<input type="email" name="email" placeholder="tu.email@ejemplo.com"
					   value="<?= isset($_COOKIE['remember_email']) ? htmlspecialchars($_COOKIE['remember_email']) : '' ?>">
				<?php if (isset($errores['email'])): ?>
					<span class="error-form"><?= htmlspecialchars($errores['email']) ?></span>
				<?php endif; ?>

				<label for="contrasenna">Contraseña:</label>
				<input type="password" name="contrasenna" placeholder="Introduce tu contraseña"
					   value="<?= isset($_COOKIE['remember_password']) ? htmlspecialchars($_COOKIE['remember_password']) : '' ?>">
				<?php if (isset($errores['contrasenna'])): ?>
					<span class="error-form"><?= htmlspecialchars($errores['contrasenna']) ?></span>
				<?php endif; ?>

				<label for="recuerdame">
					<input class="remember" type="checkbox" name="recuerdame"
						<?= isset($_COOKIE['remember_email']) ? 'checked' : '' ?>> Recuérdame
				</label>
			</fieldset>

			<!-- Botón de Inicio de Sesión -->
			<button type="submit">Iniciar Sesión</button>

			<!-- Enlace para recuperar la contraseña -->
			<div class="recuperar-contrasenna">
				<a id="recuperar-contrasenna" href="">Si no recuerdas tu contraseña, haz clic aquí.</a>
			</div>
		</form>
	</div>
</div>
<?php $this->stop() ?>

<?php $this->start('scripts') ?>

	<script>
		document.addEventListener('DOMContentLoaded', () => {
			// Agregar el evento de clic al enlace de "Recuperar Contraseña"
			document.querySelector('#recuperar-contrasenna').addEventListener('click', function() {
				// Establecer el mensaje flash en sessionStorage
				sessionStorage.setItem('flash_message', JSON.stringify({
					message: 'Se te ha enviado un correo electrónico con tu nueva contraseña.',
					type: 'success'
				}));

				// Redirigir a la página de login
				window.location.href = '/login';
			});
		});
	</script>

<?php $this->stop() ?>
