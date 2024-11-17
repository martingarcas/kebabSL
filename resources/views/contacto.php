<?php $this->layout('master'); ?>

<?php $this->start('css'); ?>
<link rel="stylesheet" href="stylesContact.css">
<?php $this->stop() ?>

<?php $this->start('formulario') ?>
<!-- Mostrar el mensaje flash, si existe -->
<?php if (isset($message)): ?>
	<div class="flash-message <?= htmlspecialchars($message['type']) ?>" id="flashMessage">
		<?= htmlspecialchars($message['message']) ?>
	</div>
<?php endif; ?>

<div class="contact-cnt">

	<span id="contact" class="anchor anchor-test anchor-contact"></span>

	<div class="contact-cnt-inner">

		<header class="contact-cnt-header">
			<h3 class="contact-form-title">CONTACTA CON NOSOTROS</h3>
			<p class="contact-description">
				Si quiere ponerse en contacto con nosotros para consultar precios, para celebrar un evento, o para obtener más información, puede rellenar este formulario, o tiene otros medios para ponerse en contacto con nosotros como nuestro email, nuestro número de móvil, a través de un mensaje de WhatsApp o viniendo directamente al estudio, ¡dónde estaremos encantados de atenderles y asesorarles de la mejor manera posible!
			</p>
		</header>

		<div class="contact-form">

			<form id="form-reset" class="contact-form-data ux-form" method="post" enctype="multipart/form-data" name="formulario">

				<label class="form-group" for="name"></label>
				<input class="form-control" type="text" name="name" data-form="name" id="nameform" placeholder="Tu nombre">

				<label class="form-group" for="email"></label>
				<input class="form-control" type="email" name="email" id="email" placeholder="Tu dirección de email">

				<label class="form-group" for="telephone"></label>
				<input class="form-control" type="tel" name="telephone" id="telephone" placeholder="Tu teléfono de contacto">

				<label class="form-group" for="comment"></label>
				<textarea class="form-control text" name="comment" cols="30" rows="6" id="comment" placeholder="Tu mensaje"></textarea>

				<label class="form-group" for="politics"></label><span class="form-control">He leído y acepto la política de privacidad.</span>
				<input class="form-control" type="checkbox" name="politics" id="politics">

				<div class="InputError">
					<span class="messageError ux-error-validate-form"></span>
				</div>

				<input class="form-send-data" type="submit" name="submit" value="ENVIAR">

			</form>

			<div class="map-responsive">
				<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3153.486614612925!2d-3.7944687!3d37.7786343!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd6dd70f9f1b7a6f%3A0xb4562ab27bdf4f45!2sRestaurante%20Kebab%20Espa%C3%B1a!5e0!3m2!1ses!2ses!4v1731842115604!5m2!1ses!2ses" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>

		</div>

		<p class="contact-description end">
			Nuestro horario: de lunes a domingo de 11:30-17:00 / 20:00-5:00
		</p>

		<p class="contact-description end">
			Recuerda que antes cualquier duda, ¡contacte con nosotros!
		</p>

	</div>
</div>

<?php $this->stop() ?>

<?php $this->start('scripts') ?>

<script>

	document.addEventListener('DOMContentLoaded', () => {
		const form = document.querySelector('.ux-form');

		form.addEventListener('submit', function (e) {
			e.preventDefault();

			const errorBox = document.querySelector('.ux-error-validate-form');

			if (!validateForm(form)) {
				console.log('No validado');
				errorBox.style.display = 'block';
				return false;
			} else {
				console.log('Validado');
				const formData = new FormData(form);
				formData.append('action', 'sendContact');
				sendForm(formData);
				setErrorForm('ENVIADO CON ÉXITO');
				setTimeout(() => setErrorForm(""), 2000);
			}

			form.reset();
		});

		function validateForm(form) {
			const name 		= form.querySelector('#nameform');
			const email 	= form.querySelector('#email');
			const comment 	= form.querySelector('#comment');
			const politics 	= form.querySelector('#politics');

			if (!name.value) {
				setErrorForm('Falta el nombre');
				return false;
			}

			if (!email.value) {
				setErrorForm('Falta el email');
				return false;
			}

			if (!comment.value) {
				setErrorForm('Falta el mensaje');
				return false;
			}

			if (!politics.checked) {
				setErrorForm('Falta el aceptar políticas de privacidad');
				return false;
			}

			return true;
		}

		function setErrorForm(errorMSG) {
			const errorBox = document.querySelector('.ux-error-validate-form');
			errorBox.innerHTML = `<span>${errorMSG}</span>`;
			errorBox.style.display = errorMSG ? 'block' : 'none';
		}

		function sendForm(dataForm) {
			fetch('/apiContact', {
				method: 'POST',
				body: dataForm
			})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						// Al enviar con éxito, establecer el mensaje flash y redirigir
						sessionStorage.setItem('flash_message', JSON.stringify({
							message: data.message || 'Mensaje enviado correctamente.',
							type: 'success'
						}));
						window.location.href = '/contacto'; // Redirigir al contacto
					} else {
						// Si hay un error, también establecer el mensaje de error
						sessionStorage.setItem('flash_message', JSON.stringify({
							message: data.message || 'Hubo un error al enviar el mensaje.',
							type: 'error'
						}));
						window.location.href = '/contacto'; // Redirigir al contacto
					}
				})
				.catch(error => {
					console.error('Error al enviar el formulario:', error);
					// En caso de error, redirigir y mostrar un mensaje flash de error
					sessionStorage.setItem('flash_message', JSON.stringify({
						message: 'Hubo un problema al enviar el mensaje. Intenta nuevamente.',
						type: 'error'
					}));
					window.location.href = '/contacto';
				});
		}
	});

</script>

<?php $this->stop() ?>
