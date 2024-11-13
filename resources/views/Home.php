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
<section class="hero">
	<h1 class="hero-title">Bienvenido a Nuestro Sitio</h1>
	<p class="hero-description">Descubre contenido increíble y aprende más sobre nosotros.</p>
	<a href="contacto" class="hero-button">Comenzar</a>
</section>
<?php $this->stop(); ?>

<?php $this->start('footer'); ?>
<h2 class="title">FOOTER</h2>
<?php $this->stop(); ?>
