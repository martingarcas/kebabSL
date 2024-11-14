<?php $this->layout('master'); ?>

<?php $this->start('css'); ?>
<link rel="stylesheet" href="stylesRegister.css">
<?php $this->stop() ?>

<?php $this->start('header') ?>
<h1 class="title">Inicio de sesión</h1>
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
				<input type="email" name="email" placeholder="tu.email@ejemplo.com">
				<?php if (isset($errores['email'])): ?>
					<span class="error-form"><?= htmlspecialchars($errores['email']) ?></span>
				<?php endif; ?>

				<label for="contrasenna">Contraseña:</label>
				<input type="password" name="contrasenna" placeholder="Introduce tu contraseña">
				<?php if (isset($errores['contrasenna'])): ?>
					<span class="error-form"><?= htmlspecialchars($errores['contrasenna']) ?></span>
				<?php endif; ?>
			</fieldset>

			<!-- Botón de Inicio de Sesión -->
			<button type="submit">Iniciar Sesión</button>
		</form>
	</div>
</div>
<?php $this->stop() ?>

<?php $this->start('footer') ?>
<h2 class="title">FOOTER</h2>
<?php $this->stop() ?>

<?php $this->start('scripts') ?>
<script>
	// Verificamos si hay un mensaje en sessionStorage
	const flashMessage = sessionStorage.getItem('flash_message');
	if (flashMessage) {
		const messageObj = JSON.parse(flashMessage);  // Parseamos el mensaje desde sessionStorage

		// Creamos un elemento div para mostrar el mensaje
		const flashMessageDiv = document.createElement('div');
		flashMessageDiv.id = 'flashMessage';
		flashMessageDiv.classList.add('flash-message', messageObj.type);  // Asignamos la clase con el tipo
		flashMessageDiv.textContent = messageObj.message;  // Asignamos el mensaje

		// Buscamos el elemento con la clase "form-wrapper"
		const formWrapper = document.querySelector('.form-wrapper');

		// Insertamos el mensaje flash justo antes de ".form-wrapper"
		if (formWrapper) {
			formWrapper.parentNode.insertBefore(flashMessageDiv, formWrapper);
		}

		// Limpiamos el sessionStorage después de mostrar el mensaje
		sessionStorage.removeItem('flash_message');
	}
</script>

<?php $this->stop() ?>
