<script>
document.addEventListener("DOMContentLoaded", function() {
	const menuIcon 		= document.getElementById('menu-icon');
	const navbar 		= document.querySelector('.navbar');
	const menuExt 		= document.querySelector('.menu');
	const mediaQuery 	= window.matchMedia('(max-width: 768px)');

	function checkMediaQuery() {
		if (mediaQuery.matches) {
			menuExt.style.display = 'block';
		}
	}

	window.addEventListener('resize', checkMediaQuery);

// Agregamos un listener para manejar el clic en el menú
	menuIcon.addEventListener('click', function() {
		// Alternar la clase 'show' para mostrar/ocultar el menú

		if (!navbar.classList.contains('show')) {
			menuExt.style.display = 'block';
			navbar.classList.add('show');

		} else {

			navbar.classList.remove('show');
			setTimeout(() => {
				menuExt.style.display = 'none';
			}, "500");
		}
	});

	setTimeout(function() {
		var flashMessage = document.querySelector('#flashMessage');
		if (flashMessage) {
			flashMessage.style.display = 'none';  // Ocultamos el mensaje
		}
	}, 5000);  // 5000 milisegundos = 5 segundos
});
</script>

