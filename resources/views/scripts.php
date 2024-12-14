<script>
document.addEventListener("DOMContentLoaded", function() {
	const menuIcon 		= document.getElementById('menu-icon');
	const navbar 		= document.querySelector('.navbar');
	const menuExt 		= document.querySelector('.menu');
	const mediaQuery 	= window.matchMedia('(min-width: 768px)');

	checkMediaQuery();

	function checkMediaQuery() {
		if (mediaQuery.matches) {
			menuExt.style.display = 'flex';
			menuExt.style.gap = '24px';
			navbar.style.display = 'block';
		} else {
			menuExt.style.display = 'none';
		}
	}

	window.addEventListener('resize', checkMediaQuery);

	// Agregamos un listener para manejar el clic en el menú
	menuIcon.addEventListener('click', function() {
		// Alternar la clase 'show' para mostrar/ocultar el menú

		if (!navbar.classList.contains('show')) {
			menuExt.style.display = 'block';
			navbar.style.display = 'block';
			setTimeout(() => {
				navbar.classList.add('show');
			}, "100");
			document.body.style.overflow = 'hidden';

		} else {

			navbar.classList.remove('show');
			setTimeout(() => {
				menuExt.style.display = 'none';
				navbar.style.display = 'none';
			}, "500");
			document.body.style.overflow = '';
		}
	});

	function obtenerFlash() {
		// Verificamos si hay un mensaje en sessionStorage
		const flashMessage = sessionStorage.getItem('flash_message');
		if (flashMessage) {
			const messageObj = JSON.parse(flashMessage);  // Parseamos el mensaje

			const flashMessageDiv = document.createElement('div');
			flashMessageDiv.id = 'flashMessage';
			flashMessageDiv.classList.add('flash-message', messageObj.type);  // Asignamos la clase con el tipo (success)
			flashMessageDiv.textContent = messageObj.message;  // Asignamos el mensaje

			const formWrapper = document.querySelector('.form-wrapper');

			// Insertamos el mensaje flash justo antes de ".form-wrapper" (contenedor padre)
			if (formWrapper) {
				formWrapper.parentNode.insertBefore(flashMessageDiv, formWrapper);
			}

			// Limpiamos el sessionStorage después de mostrar el mensaje
			sessionStorage.removeItem('flash_message');
		}

		setTimeout(function() {
			var flashMessages = document.querySelectorAll('#flashMessage');
			if (flashMessages) {
				flashMessages.forEach(function (flash) {
					flash.style.display = 'none';
				});
				// flashMessage.style.display = 'none';  // Ocultamos el mensaje
			}
		}, 5000);  // 5000 milisegundos = 5 segundos
	}

	// Obtener el valor de carrito_cantidad desde sessionStorage
	const carritoCantidadDisplay = document.getElementById('carrito-cantidad-display');

	// Actualizar el contenido del elemento con la cantidad del carrito
	const actualizarCarritoCantidadDisplay = () => {
		if (sessionStorage.getItem('carrito_cantidad')) {

			const carritoCantidad = parseInt(sessionStorage.getItem('carrito_cantidad'), 10) || 0;
			carritoCantidadDisplay.textContent = carritoCantidad > 0 ? `(${carritoCantidad})` : '';
		}
	}

	// Iniciar la actualización inicial
	actualizarCarritoCantidadDisplay();

	// Escuchar el evento de actualización del carrito
	document.addEventListener('carritoActualizado', (event) => {
		obtenerFlash();
		carritoCantidadDisplay.textContent = `(${event.detail.carritoCantidad})`;
	});

	obtenerFlash();
});
</script>

