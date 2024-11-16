<footer>

	<div class="footer-cnt">

		<div class="footer-cnt-inner">

			<nav class="main-data">



				<div class="main-data-box impar">
					<header class="main-menu-footer-logo"><img class="main-menu-footer-logo" src="/img/logo/kebablogowhite.png" alt="kebabsl"></header>
					<ul class="main-data-box-list list-rrss">
						<li class="list-item">
							<a class="d-flex list-item-rrss" href="https://wa.me/" target="_blank"><img class="icon" width="20" height="25" src="/img/iconos/whatsapp.svg" alt="whatsapp"></a>
						</li>
						<li class="list-item">
							<a class="d-flex list-item-rrss" href="https://www.instagram.com/" target="_blank"><img class="icon" width="20" height="25" src="/img/iconos/instagram.svg" alt="instagram"></a>
						</li>
						<li class="list-item">
							<a class="d-flex list-item-rrss" href="https://es-es.facebook.com/" target="_blank"><img class="icon" width="20" height="25" src="/img/iconos/facebook.svg" alt="facebook"></a>
						</li>
					</ul>
				</div>

				<div class="main-data-box par">

					<header class="main-data-box-header h2">Información</header>

					<ul class="main-data-box-list">
						<li class="list-item">
							<span class="material-icons-outlined">mail</span>
							<a href="mailto:martingar1997@hotmail.com">kebabsl@gmail.com</a>
						</li>
						<li class="list-item">
							<span class="material-icons">phonelink_ring</span>
							<a href="tel:693209523" target="_blank">(+34) 693 20 95 23</a>
						</li>
						<li class="list-item">
							<span class="material-icons-outlined">place</span>
							<a href="https://maps.app.goo.gl/YzHb53DVPXKmbZ9V6" target="_blank">
								C. Enrique Ponce, 4<br/>
								C.P: 23006 · Jaén
							</a>
						</li>
					</ul>

				</div>



				<div class="main-data-box impar">

					<header class="main-data-box-header h2">Navegación</header>
					<ul class="main-data-box-list">
						<!-- Menú para usuarios logueados -->
						<?php if ($usuario !== null && $usuario->getRol() === 'cliente'): ?>
						<li class="list-item"><a href="/">Carta</a></li>
						<li class="list-item"><a href="/">Carrito</a></li>
						<li class="list-item"><a href="/">Contacto</a></li>
						<li class="list-item"><a href="/profile">Mi perfil</a></li>
						<li class="list-item"><a href="/logout">Cerrar sesión</a></li>
						<?php elseif ($usuario !== null && $usuario->getRol() === 'administrador'): ?>
						<li class="list-item"><a href="/">Kebabs</a></li>
						<li class="list-item"><a href="/ingredientes">Ingredientes</a></li>
						<li class="list-item"><a href="/">Gráficos-Estados</a></li>
						<li class="list-item"><a href="/profile">Mi perfil</a></li>
						<li class="list-item"><a href="/logout">Cerrar sesión</a></li>
						<?php else: ?>
						<li class="list-item"><a href="/login">Iniciar Sesión</a></li>
						<li class="list-item"><a href="/register">Regístrarse</a></li>
						<li class="list-item"><a href="/">Carta</a></li>
						<li class="list-item"><a href="/">Carrito</a></li>
						<li class="list-item"><a href="/">Contacto</a></li>
						<?php endif; ?>
					</ul>

				</div>

				<div class="main-data-box par">

					<header class="main-data-box-header h2">Legal</header>
					<ul class="main-data-box-list">
						<li class="list-item"><a href="">Aviso Legal</a></li>
						<li class="list-item"><a href="">Política de Privacidad</a></li>
						<li class="list-item"><a href="">Política de Cookies</a></li>
					</ul>

				</div>


			</nav>

		</div>

	</div>

</footer>