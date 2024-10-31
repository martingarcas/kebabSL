<?php $this->layout('master'); ?>

<?php $this->start('head'); ?>

    <title>HOME</title>

<?php $this->stop() ?>


<?php $this->start('header') ?>
<h1 class="title">Welcome!</h1>
<?php $this->stop() ?>

<?php $this->start('listado') ?>
<h1>Lista de Usuarios</h1>
<ul>
    <?php foreach ($users as $user): ?>
        <li><?= $user['name']; ?> - <?= $user['email']; ?> </li>
    <?php endforeach; ?>

</ul>

<h1>Lista de Kebabs</h1>
<div>
	<?php
	use App\Models\Listador;
    Listador::listar($kebabs); ?> <!-- Llamamos al método listar aquí -->
</div>

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
