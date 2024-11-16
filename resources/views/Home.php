<?php use App\Utils\Logger; ?>

<?php $this->layout('master'); ?>

<?php $this->start('head'); ?>
<title>HOME</title>
<?php $this->stop(); ?>

<?php $this->start('header'); ?>

<!-- Mostrar el mensaje flash, si existe -->
<?php if (isset($message)): ?>
	<div class="flash-message <?= htmlspecialchars($message['type']) ?>" id="flashMessage">
		<?= htmlspecialchars($message['message']) ?>
	</div>
<?php endif; ?>

<?php
// Verificar si el usuario está logueado
//$usuario = Logger::obtenerUsuario();  // Esto obtiene el usuario desde la sesión
?>

<h1 class="title">
</h1>

<?php $this->stop(); ?>

<?php $this->start('seccion-prueba'); ?>
	<img src="/img/alergenos/altramuz.png" alt="altramuz" style="display: block; width: 180px; height: 180px">
<?php $this->stop(); ?>

