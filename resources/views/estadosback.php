<?php $this->layout('master'); ?>
<?php $this->start('css'); ?>
	<link rel="stylesheet" href="stylesRegister.css">
	<link rel="stylesheet" href="stylesCarrito.css">

	<style>
		main {
			display: flex;
			align-items: center;
			justify-content: center;
			height: 100vh;
			font-weight: 600;
			font-family: system-ui;
		}

		section {
			text-align: center;
		}

		section h1 {
			font-size: 2em;
			margin-bottom: 1rem;
		}

		.container {
			display: flex;
			justify-content: center;
			align-items: center;
			gap: 2rem;
		}

		.ingredients_zone {
			width: 12rem;
			min-height: 15rem;
			padding: 1rem;
			border: 0.25rem dashed #74b087;
			border-radius: 0.5rem;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: flex-start;
			gap: 0.5rem;
			background-color: #f8f8f8;
		}

		.ingredients_zone#target {
			background-color: #e8e8e8;
		}

		.ingredient {
			background: #de7300;
			color: white;
			padding: 0.5rem 1rem;
			border-radius: 0.5rem;
			cursor: pointer;
			text-align: center;
			transition: transform 0.2s;
		}

		.ingredient:active {
			transform: scale(1.1);
		}

	</style>
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
<!---->
<!--	<div style="display: block; width: 400px;height: 300px;">-->
<!--		<canvas id="myChart"></canvas>-->
<!--	</div>-->

	<main>
		<section>
			<h1>Drag Ingredients</h1>
			<div class="container">
				<!-- Caja con los ingredientes -->
				<div class="ingredients_zone" id="source">
					<div class="ingredient" draggable="true">Ingrediente 1</div>
					<div class="ingredient" draggable="true">Ingrediente 2</div>
					<div class="ingredient" draggable="true">Ingrediente 3</div>
					<div class="ingredient" draggable="true">Ingrediente 4</div>
				</div>
				<!-- Caja vacía -->
				<div class="ingredients_zone" id="target"></div>
			</div>
		</section>
	</main>



<?php $this->stop() ?>

<?php $this->start('scripts'); ?>

	<script>
		window.addEventListener('load', function () {

			// Selecciona las zonas y los elementos
			const sourceZone = document.getElementById("source");
			const targetZone = document.getElementById("target");
			const ingredients = document.querySelectorAll(".ingredient");

			// Sonido al soltar el ingrediente
			const sound = new Audio("https://assets.mixkit.co/active_storage/sfx/3005/3005-preview.mp3");

// Eventos para manejar el arrastre
			ingredients.forEach((ingredient) => {
				ingredient.addEventListener("dragstart", (e) => {
					e.dataTransfer.setData("text", e.target.innerText);
					setTimeout(() => ingredient.classList.add("hide"), 0);
				});

				ingredient.addEventListener("dragend", () => {
					ingredient.classList.remove("hide");
				});
			});

// Permitir arrastrar a las zonas
			function dragEnter(e) {
				e.preventDefault();
				this.classList.add("dragover");
			}

			function dragOver(e) {
				e.preventDefault();
			}

			function dragLeave() {
				this.classList.remove("dragover");
			}

			function drop(e) {
				e.preventDefault();
				const text = e.dataTransfer.getData("text");
				const draggedIngredient = [...document.querySelectorAll(".ingredient")].find(
					(ing) => ing.innerText === text
				);

				if (draggedIngredient) {
					this.appendChild(draggedIngredient);
					sound.play();
				}
				this.classList.remove("dragover");
			}

// Asociar eventos a ambas zonas
			[sourceZone, targetZone].forEach((zone) => {
				zone.addEventListener("dragenter", dragEnter);
				zone.addEventListener("dragover", dragOver);
				zone.addEventListener("dragleave", dragLeave);
				zone.addEventListener("drop", drop);
			});

		});
	</script>


<!--	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>-->
<!---->
<!--	<script>-->
<!--		window.addEventListener("load", function () {-->
<!---->
<!--			const ctx = document.getElementById('myChart');-->
<!---->
<!---->
<!--			traerVentas().then(datos=>{-->
<!--				let labels = [];-->
<!--				let data = [];-->
<!--				datos.forEach(item => {-->
<!--					labels.push(item.nombre);-->
<!--					data.push(item.cantidad);-->
<!--				})-->
<!---->
<!---->
<!--				ctx.grafico = new Chart(ctx, {-->
<!--					type: 'line',-->
<!--					data: {-->
<!--						labels: labels,-->
<!--						datasets: [{-->
<!--							label: 'Unidades vendidas',-->
<!--							data: data,-->
<!--							borderWidth: 1-->
<!--						}]-->
<!--					},-->
<!--					options: {-->
<!--						scales: {-->
<!--							y: {-->
<!--								beginAtZero: true-->
<!--							}-->
<!--						},-->
<!--						responsive: true,-->
<!--					}-->
<!--				});-->
<!--			})-->
<!---->
<!--			window.setInterval(function(){-->
<!--				traerVentas().then(datos=>{-->
<!--					let labels = [];-->
<!--					let data = [];-->
<!--					datos.forEach(item => {-->
<!--						labels.push(item.nombre);-->
<!--						data.push(item.cantidad);-->
<!--					});-->
<!--					ctx.grafico.data.labels = labels;-->
<!--					ctx.grafico.data.datasets[0].data = data;-->
<!--					ctx.grafico.update('active');-->
<!--					ctx.grafico.render();-->
<!--				})-->
<!--			},2000)-->
<!---->
<!--			//Funcion que pide las ventas de kebab y devuelve matriz con objetos-->
<!---->
<!--			async function traerVentas(){-->
<!--				var peticion = await fetch("datos.php");-->
<!--				var obj = await peticion.json();-->
<!--				return obj;-->
<!--			}-->
<!---->
<!--		})-->
<!--	</script>-->

<?php $this->stop(); ?>