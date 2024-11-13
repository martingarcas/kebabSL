<?php
use App\Utils\Logger;

// Recupera el objeto Usuario desde la sesión
$usuario = Logger::obtenerUsuario();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="/">KEBAB S.L.</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<!-- Otros enlaces del menú comunes -->

			<!-- Mostrar diferentes enlaces dependiendo de si el usuario está logueado y si es administrador -->
			<?php if ($usuario): ?>
				<li class="nav-item active">
					<a class="nav-link" href="/logout">CERRAR SESIÓN</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="/profile">MI PERFIL</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="#">Hola, <?= htmlspecialchars($usuario->getNombre()); ?>!</a> <!-- Mostrar el nombre del usuario -->
				</li>

				<!-- Verificar si el usuario es administrador -->
				<?php if ($usuario->getRol() === 'administrador'): ?>
					<!-- Mostrar menú para administradores -->
					<li class="nav-item active">
						<a class="nav-link" href="/admin/dashboard">DASHBOARD ADMIN</a>
					</li>
					<li class="nav-item active">
						<a class="nav-link" href="/admin/gestionar-usuarios">GESTIONAR USUARIOS</a>
					</li>
					<li class="nav-item active">
						<a class="nav-link" href="/admin/gestionar-productos">GESTIONAR PRODUCTOS</a>
					</li>
				<?php endif; ?>

			<?php else: ?>
				<!-- Menú para usuarios no logueados -->
				<li class="nav-item active">
					<a class="nav-link" href="/login">INICIAR SESIÓN <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="/register">REGÍSTRARSE <span class="sr-only">(current)</span></a>
				</li>
			<?php endif; ?>
		</ul>

		<!-- Formulario de búsqueda -->
		<form class="form-inline my-2 my-lg-0">
			<input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search">
			<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
		</form>
	</div>
</nav>
