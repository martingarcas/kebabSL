<?php
use App\Utils\Logger;

// Recupera el objeto Usuario desde la sesión
$usuario = Logger::obtenerUsuario();
?>

<header class="header">
	<div class="wrap">
		<h2 class="logo"><a href="/">KEBAB S.L.</a></h2>
		<a id="menu-icon">&#9776; Menu</a> <!-- Icono del menú hamburguesa -->
		<nav class="navbar">
			<ul class="menu">
				<!-- Menú para usuarios logueados -->
				<?php if ($usuario !== null && $usuario->getRol() === 'cliente'): ?>
					<li><a href="/">CARTA</a></li>
					<li><a href="/">CARRITO</a></li>
					<li><a href="/">CONTACTO</a></li>
					<!-- Foto del usuario con submenú desplegable -->
					<li class="user-menu">
						<a href="#" class="user-link">
							<img src="<?= htmlspecialchars($usuario->getFoto()); ?>" alt="Foto de <?= htmlspecialchars($usuario->getNombre()); ?>" class="user-photo">
						</a>
						<ul class="submenu">
							<li><a href="/profile">MI PERFIL</a></li>
							<li><a href="/logout">CERRAR SESIÓN</a></li>
						</ul>
					</li>
				<?php elseif ($usuario !== null && $usuario->getRol() === 'administrador'): ?>
					<!-- Menú para administradores -->
					<li><a href="/">KEBABS</a></li>
					<li><a href="/">INGREDIENTES</a></li>
					<li><a href="/">GRÁFICOS-ESTADOS</a></li>
					<li class="user-menu">
						<a href="#" class="user-link">
							<img src="<?= htmlspecialchars($usuario->getFoto()); ?>" alt="Foto de <?= htmlspecialchars($usuario->getNombre()); ?>" class="user-photo">
						</a>
						<ul class="submenu">
							<li><a href="/profile">MI PERFIL</a></li>
							<li><a href="/logout">CERRAR SESIÓN</a></li>
						</ul>
					</li>
				<?php else: ?>
					<!-- Menú para usuarios no logueados -->
					<li><a href="/login">INICIAR SESIÓN</a></li>
					<li><a href="/register">REGÍSTRARSE</a></li>
					<li><a href="/">CARTA</a></li>
					<li><a href="/">CARRITO</a></li>
					<li><a href="/">CONTACTO</a></li>
				<?php endif; ?>
			</ul>
		</nav>
	</div>
</header>

<!-- Si es necesario un formulario de búsqueda -->
<div class="content">
	<h2>Simple Responsive Navigation Menu</h2>
	<p>Built with CSS.</p>
</div>

<?php $this->start('scripts'); ?>
<script>
	document.addEventListener("DOMContentLoaded", function() {
		const menuIcon = document.getElementById('menu-icon');
		const navbar = document.querySelector('.navbar');

// Agregamos un listener para manejar el clic en el menú
		menuIcon.addEventListener('click', function() {
			// Alternar la clase 'show' para mostrar/ocultar el menú
			navbar.classList.toggle('show');
		});
	});

</script>
<?php $this->stop(); ?>
