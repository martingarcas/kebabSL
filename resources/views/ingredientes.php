<?php $this->layout('master'); ?>
<?php $this->start('css'); ?>

<link rel="stylesheet" href="stylesRegister.css">

<?php $this->stop() ?>

<?php $this->start('header') ?>
<?php $this->stop() ?>
<!-- Mostrar el mensaje flash, si existe -->
<?php if (isset($message)): ?>
    <div class="flash-message <?= htmlspecialchars($message['type']) ?>" id="flashMessage">
		<?= htmlspecialchars($message['message']) ?>
    </div>
<?php endif; ?>

<?php $this->start('formulario') ?>
<div class="form-wrapper">
    <div class="form-container">
        <h2>INGREDIENTES</h2>

		<div class="tarjetas-container"></div>

        <form action="/register-post" method="POST" class="formulario__register">

            <div class="form-fielsets">
                <!-- Datos Personales -->
                <fieldset>

                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" placeholder="Tu nombre" value="">

                    <label for="foto">Foto:</label>
                    <input type="file" name="file" placeholder="" value="">

                    <label for="dni">Precio:</label>
                    <input type="text" name="precio" placeholder="Precio" value="" required>

                    <div class="alergenos"></div>
                </fieldset>

            </div>

            <!-- Botón de registro -->
            <button type="submit">CREAR INGREDIENTE</button>
        </form>
        <div class="ux-cnt-container"></div>
    </div>
</div>

<?php $this->stop() ?>

<?php $this->start('scripts'); ?>

<script>

	document.addEventListener('DOMContentLoaded', () => {
		const tarjetasContainer = document.querySelector('.tarjetas-container');

		async function obtenerIngredientesYAlergenos() {
			const formData = new FormData();
			formData.append('action', 'show');

			const response = await fetch('/apiIngrediente', { method: 'POST', body: formData });
			const data = await response.json();

			if (data.success) {
				const ingredientes = data.ingredientes;
				const alergenos = data.alergenos;

				// Crear tarjetas de ingredientes
				ingredientes.forEach(ingrediente => {
					const card = crearCardIngrediente(ingrediente, alergenos);
					tarjetasContainer.appendChild(card);
				});
			}
		}

		function crearCardIngrediente(ingrediente, alergenos) {
			const card = document.createElement('div');
			card.classList.add('ingrediente-card');

			// Foto del ingrediente
			const img = document.createElement('img');
			img.src = ingrediente.foto;
			img.alt = ingrediente.nombre;
			card.appendChild(img);

			// Nombre del ingrediente
			const nombre = document.createElement('h3');
			nombre.textContent = ingrediente.nombre;
			card.appendChild(nombre);

			// Precio del ingrediente
			const precio = document.createElement('p');
			precio.classList.add('precio');
			precio.textContent = `${ingrediente.precio}€`;
			card.appendChild(precio);

			// Contenedor de alérgenos
			const alergenosContainer = document.createElement('div');
			alergenosContainer.classList.add('alergenos-container');

			ingrediente.alergenos.forEach(alergenoId => {
				const alergeno = alergenos.find(a => a.id === alergenoId);

				if (alergeno) {
					const imgAlergeno = document.createElement('img');
					imgAlergeno.classList.add('alergeno-img');
					imgAlergeno.src = alergeno.foto;
					imgAlergeno.alt = alergeno.nombre;

					// Tooltip con el nombre del alérgeno
					const tooltip = document.createElement('span');
					tooltip.classList.add('tooltip');
					tooltip.textContent = alergeno.nombre;

					alergenosContainer.appendChild(imgAlergeno);
					alergenosContainer.appendChild(tooltip);
				}
			});

			card.appendChild(alergenosContainer);

			return card;
		}

		// Obtener los ingredientes y alérgenos al cargar la página
		obtenerIngredientesYAlergenos();
	});


</script>

<?php $this->stop(); ?>
