<?php use App\Utils\Logger;

$this->layout('master'); ?>

<?php $this->start('head'); ?>

    <title>HOME</title>

<?php $this->stop() ?>


<?php $this->start('header') ?>
<h1 class="title">
	<?php if (isset($usuario)): ?>
		Hola, <?= htmlspecialchars($usuario->getNombre()); ?>!
	<?php else: ?>
		Hola, Invitado!
	<?php endif; ?>

</h1>
<?php $this->stop() ?>


<?php $this->start('seccion-prueba') ?>
    <section class="hero">
        <h1 class="hero-title">Bienvenido a Nuestro Sitio</h1>
        <p class="hero-description">Descubre contenido increíble y aprende más sobre nosotros.</p>
        <a href="contacto" class="hero-button">Comenzar</a>
    </section>

<?php $this->stop() ?>

<?php $this->start('footer') ?>
<h2 class="title">FOOTER</h2>
<?php $this->stop() ?>
