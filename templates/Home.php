<?php $this->layout('layout'); ?>


<?php $this->start('header') ?>
<h1 class="title">Welcome!</h1>
<nav class="navbar navbar-expand-lg navbar-light bg-light">

		<a class="navbar-brand" href="#">KEBAB S.L.</a>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">

			<ul class="navbar-nav mr-auto">
				
				<li class="nav-item active">
					<a class="nav-link" href="?menu=mantenimiento">MANTENIMIENTO <span class="sr-only">(current)</span></a>
				</li>

				<li class="nav-item dropdown">
					
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
						data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						LISTADOS
					</a>

					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="?menu=listadoanimales">ANIMALES</a>
						<a class="dropdown-item" href="?menu=listadovacunas">VACUNACIONES</a>
					</div>

				</li>
				
                <li class="nav-item active">
                    <a class="nav-link" href="?menu=login">INICIAR SESIÓN <span class="sr-only">(current)</span></a>
                </li>

			</ul>

<!--SESSION-->

			<form class="form-inline my-2 my-lg-0">

				<input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
				
			</form>

		</div>

	</nav>
<?php $this->stop() ?>

<?php $this->start('listado') ?>
<h1>Lista de Usuarios</h1>
<ul>
<?php foreach ($users as $user): ?>
        <li><?= $user['name']; ?> - <?= $user['email']; ?> </li>
    <?php endforeach; ?>

</ul>
<?php $this->stop() ?>

<?php $this->start('seccion-prueba') ?>
    <section class="hero">
        <h1 class="hero-title">Bienvenido a Nuestro Sitio</h1>
        <p class="hero-description">Descubre contenido increíble y aprende más sobre nosotros.</p>
        <a href="?menu=contacto" class="hero-button">Comenzar</a>
    </section>
<?php $this->stop() ?>

<?php $this->start('footer') ?>
<h2 class="title">FOOTER</h2>
<?php $this->stop() ?>
