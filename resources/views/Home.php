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
<div class="presentation-cnt">
	<div class="presentation-cnt-inner">

		<header class="presentation-header-cnt">

			<div class="presentation-header-title">
				<h1 class="main-title">¡El mejor Kebab de toda la ciudad!</h1>
			</div>


			<div class="presentation-header-introduction">

				<p class="presentation-paragraph">
					Dicen que una experiencia culinaria puede quedarse grabada en la memoria mucho más que una simple comida.
				</p>

				<p class="presentation-paragraph">
					Como un recuerdo inolvidable, nuestros kebabs son la combinación perfecta de ingredientes frescos, carnes de la más alta calidad y un toque especial que te transporta a un lugar donde el sabor es el protagonista.				</p>

				<p class="presentation-paragraph">
					En <strong>Doner Kebab S.L.</strong> hemos hecho de eso nuestra misión: ofrecerte no solo un kebab, sino una experiencia única que disfrutarás en cada bocado.
				</p>

			</div>

		</header>

		<div class="presentation-cnt-body">

			<div class="presentation-main-body">

				<div class="info-box information-main-img-cnt">
					<img class="main-img" src="/img/ingredientes/kebab2.png" alt="information">
				</div>

				<div class="info-box information-main-comment">

					<p class="information-paragraph">
						Cada día nos esforzamos por sorprenderte con nuestra amplia variedad de carnes, cocinadas a la perfección y servidas en un pan suave y delicioso.
					</p>

					<p class="information-paragraph">
						Desde el clásico de siempre hasta opciones innovadoras, cada receta es elaborada con esmero y pasión para garantizar que cada visita sea especial.
					</p>

				</div>

			</div>

			<div class="presentation-main-body information-main-reverse end-separator">

				<div class="info-box information-main-img-cnt">
					<img class="main-img" src="/img/ingredientes/kebab3.jpg" alt="information">
				</div>

				<div class="info-box information-main-comment">

					<p class="information-paragraph">
						Nuestras instalaciones son modernas y acogedoras, diseñadas para ofrecerte el mejor ambiente para disfrutar de tu kebab.
					<p class="information-paragraph">
						Con un servicio rápido y atento, cada detalle está pensado para que te sientas como en casa, en un espacio donde el sabor y la comodidad se encuentran.
					</p>

					<p class="information-paragraph">
						En <strong>Doner Kebab S.L.</strong> sabemos que el mejor momento del día es el que compartes con un buen kebab en la mano. Ven, siéntate y déjanos hacer de tu comida un momento inolvidable.
					</p>

				</div>

			</div>

		</div>

	</div>
</div>
<?php $this->stop(); ?>

