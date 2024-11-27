<?php
use App\Utils\Logger;

// Recupera el objeto Usuario desde la sesión
$usuario = Logger::obtenerUsuario();
var_dump($usuario);
?>

<header class="header">
	<div class="wrap">
		<header class="main-menu-logo"><a href="/"><img class="main-menu-logo" src="/img/logo/kebablogo.png" alt="kebabsl"></a></header>
		<a id="menu-icon">&#9776; Menu</a> <!-- Icono del menú hamburguesa -->
		<nav class="navbar">
			<ul class="menu">
				<!-- Menú para usuarios logueados -->
				<?php if ($usuario !== null && $usuario->getRol() === 'cliente'): ?>
					<li><a href="/">Carta</a></li>
					<li><a href="/">Carrito</a></li>
					<li><a href="/contacto">Contacto</a></li>
					<!-- Foto del usuario con submenú desplegable -->
					<li class="user-menu">
						<a href="#" class="user-link">
							<img src="<?= htmlspecialchars($usuario->getFoto()); ?>" alt="Foto de <?= htmlspecialchars($usuario->getNombre()); ?>" class="user-photo">
						</a>
						<ul class="submenu">
							<li><a href="/profile">Mi perfil</a></li>
							<li><a href="/logout">Cerrar sesión</a></li>
						</ul>
					</li>
				<?php elseif ($usuario !== null && $usuario->getRol() === 'administrador'): ?>
					<!-- Menú para administradores -->
					<li><a href="/">Kebabs</a></li>
					<li><a href="/ingredientes">Ingredientes</a></li>
					<li><a href="/">Gráficos-estados</a></li>
					<li class="user-menu">
						<a href="#" class="user-link">
							<img src="<?= htmlspecialchars($usuario->getFoto()); ?>" alt="Foto de <?= htmlspecialchars($usuario->getNombre()); ?>" class="user-photo">
						</a>
						<ul class="submenu">
							<li><a href="/profile">Mi perfil</a></li>
							<li><a href="/logout">Cerrar sesión</a></li>
						</ul>
					</li>
				<?php else: ?>
					<!-- Menú para usuarios no logueados -->
					<li><a href="/">Inicio</a></li>
					<li><a href="/">Carta</a></li>
					<li><a href="/">Carrito</a></li>
					<li><a href="/contacto">Contacto</a></li>
					<li><a href="/login">Iniciar sesión</a></li>
					<li><a href="/register">Regístrate</a></li>
				<?php endif; ?>
			</ul>
		</nav>
	</div>
</header>
