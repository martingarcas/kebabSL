<?php $this->layout('master'); ?>
<?php $this->start('css'); ?>

<link rel="stylesheet" href="stylesRegister.css">

<?php $this->stop() ?>

<?php $this->start('header') ?>
<h1 class="title">Inicio de sesión</h1>
<?php $this->stop() ?>

<?php $this->start('formulario') ?>
<?php if (isset($message)): ?>
	<div class="alert alert-success">
		<?= htmlspecialchars($message) ?>
	</div>
<?php endif; ?>

<div class="form-wrapper">
	<div class="form-container">
		<h2>Iniciar Sesión</h2>
		<form action="/login-post" method="POST" class="formulario__login">
			<!-- Datos de Inicio de Sesión -->
			<fieldset>
				<legend>Inicio de Sesión</legend>

				<label for="email">Correo Electrónico:</label>
				<input type="email" name="email" placeholder="tu.email@ejemplo.com" value="<?= isset($data['email']) ? $data['email'] : ''; ?>" required>
				<?php if (isset($errores['email'])) : ?>
					<span class="error"><?= $errores['email']; ?></span>
				<?php endif; ?>

				<label for="contrasenna">Contraseña:</label>
				<input type="password" name="contrasenna" placeholder="Introduce tu contraseña" required>
				<?php if (isset($errores['contrasenna'])) : ?>
					<span class="error"><?= $errores['contrasenna']; ?></span>
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

<?php $this->stop() ?>
