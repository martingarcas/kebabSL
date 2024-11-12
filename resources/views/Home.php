<?php $this->layout('master'); ?>

<?php $this->start('head'); ?>

    <title>HOME</title>

<?php $this->stop() ?>


<?php $this->start('header') ?>
<h1 class="title">Welcome!</h1>
<?php $this->stop() ?>

<!-- Mostrar el mensaje de éxito si el parámetro 'success' está en la URL -->


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
