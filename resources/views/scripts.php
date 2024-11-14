<script>
document.addEventListener("DOMContentLoaded", function() {
	const menuIcon = document.getElementById('menu-icon');
	const navbar = document.querySelector('.navbar');

// Agregamos un listener para manejar el clic en el menú
	menuIcon.addEventListener('click', function() {
		// Alternar la clase 'show' para mostrar/ocultar el menú
		navbar.classList.toggle('show');
	});

	setTimeout(function() {
		var flashMessage = document.querySelector('#flashMessage');
		if (flashMessage) {
			flashMessage.style.display = 'none';  // Ocultamos el mensaje
		}
	}, 5000);  // 5000 milisegundos = 5 segundos
});
</script>

