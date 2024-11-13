<?php $this->layout('master'); ?>
<?php $this->start('css'); ?>

<link rel="stylesheet" href="stylesRegister.css">

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
<div class="form-wrapper">
	<div class="form-container">
		<h2>Formulario de Registro</h2>
		<form action="/register-post" method="POST" class="formulario__register">
			<!-- Datos Personales -->
			<fieldset>
				<legend>Datos Personales</legend>

				<label for="nombre">Nombre:</label>
				<input type="text" name="nombre" placeholder="Tu nombre" value="<?= isset($data['nombre']) ? $data['nombre'] : ''; ?>">
				<?php if (isset($errores['nombre'])) : ?>
					<span class="error-form"><?= $errores['nombre']; ?></span>
				<?php endif; ?>

				<label for="email">Correo Electrónico:</label>
				<input type="email" name="email" placeholder="tu.email@ejemplo.com" value="<?= isset($data['email']) ? $data['email'] : ''; ?>">
				<?php if (isset($errores['email'])) : ?>
					<span class="error-form"><?= $errores['email']; ?></span>
				<?php endif; ?>

				<label for="dni">DNI:</label>
				<input type="text" name="dni" placeholder="DNI" value="<?= isset($data['dni']) ? $data['dni'] : ''; ?>" required>
				<?php if (isset($errores['dni'])) : ?>
					<span class="error-form"><?= $errores['dni']; ?></span>
				<?php endif; ?>

				<label for="contrasenna">Contraseña:</label>
				<input type="password" name="contrasenna" placeholder="Crea una contraseña" value="<?= isset($data['contrasenna']) ? $data['contrasenna'] : ''; ?>">
				<?php if (isset($errores['contrasenna'])) : ?>
					<span class="error-form"><?= $errores['contrasenna']; ?></span>
				<?php endif; ?>
			</fieldset>

			<!-- Dirección -->
			<fieldset>
				<legend>Dirección</legend>
				<label for="calle">Calle:</label>
				<input type="text" name="calle" placeholder="Calle de residencia" value="<?= isset($data['calle']) ? $data['calle'] : ''; ?>">
				<?php if (isset($errores['calle'])) : ?>
					<span class="error-form"><?= $errores['calle']; ?></span>
				<?php endif; ?>

				<label for="numero">Número:</label>
				<input type="text" name="numero" placeholder="Número de tu domicilio" value="<?= isset($data['numero']) ? $data['numero'] : ''; ?>">
				<?php if (isset($errores['numero'])) : ?>
					<span class="error-form"><?= $errores['numero']; ?></span>
				<?php endif; ?>
			</fieldset>

			<!-- Botón de registro -->
			<button type="submit">Registrarse</button>
		</form>
	</div>
</div>

<?php $this->stop() ?>

<?php $this->start('footer') ?>
<h2 class="title">FOOTER</h2>
<?php $this->stop() ?>

<?php $this->start('scripts') ?>

<?php $this->stop() ?>
