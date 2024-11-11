<?php $this->layout('master'); ?>
<?php $this->start('css'); ?>

    <link rel="stylesheet" href="stylesRegister.css">

<?php $this->stop() ?>

<?php $this->start('header') ?>
<h1 class="title">REGISTRO</h1>
<?php $this->stop() ?>

<?php $this->start('formulario') ?>
	<main>

		<div class="contenedor__todo">
			<div class="caja__trasera">
				<div class="caja__trasera-login">
					<h3>¿Ya tienes una cuenta?</h3>
					<p>Inicia sesión para entrar en la página</p>
					<button id="btn__iniciar-sesion">Iniciar Sesión</button>
				</div>
				<div class="caja__trasera-register">
					<h3>¿Aún no tienes una cuenta?</h3>
					<p>Regístrate para que puedas iniciar sesión</p>
					<button id="btn__registrarse">Regístrarse</button>
				</div>
			</div>

			<!--Formulario de Login y registro-->
			<div class="contenedor__login-register">
				<!--Login-->
				<form action="" class="formulario__login">
					<h2>Iniciar Sesión</h2>
					<input type="text" placeholder="Correo Electronico">
					<input type="password" placeholder="Contraseña">
					<button>Entrar</button>
				</form>

				<!--Register-->
				<form action="../../app/Controllers/RegisterController.php" class="formulario__register">
					<h2>Regístrarse</h2>
					<input type="text" placeholder="Nombre" required>
					<input type="text" placeholder="Correo Electronico" required>
					<input type="text" placeholder="DNI" required>
					<input type="password" placeholder="Contraseña" required>
					<br><br>
					<h2>Dirección</h2>
					<input type="text" placeholder="Calle" required>
					<input type="text" placeholder="Número" required>
					<button>Regístrarse</button>
				</form>
			</div>
		</div>

	</main>

<?php $this->stop() ?>

<?php $this->start('footer') ?>
<h2 class="title">FOOTER</h2>
<?php $this->stop() ?>

<?php $this->start('scripts') ?>
<script>
	//Ejecutando funciones
	document.getElementById("btn__iniciar-sesion").addEventListener("click", iniciarSesion);
	document.getElementById("btn__registrarse").addEventListener("click", register);
	window.addEventListener("resize", anchoPage);

	//Declarando variables
	var formulario_login = document.querySelector(".formulario__login");
	var formulario_register = document.querySelector(".formulario__register");
	var contenedor_login_register = document.querySelector(".contenedor__login-register");
	var caja_trasera_login = document.querySelector(".caja__trasera-login");
	var caja_trasera_register = document.querySelector(".caja__trasera-register");

	//FUNCIONES

	function anchoPage(){

		if (window.innerWidth > 850){
			caja_trasera_register.style.display = "block";
			caja_trasera_login.style.display = "block";
		}else{
			caja_trasera_register.style.display = "block";
			caja_trasera_register.style.opacity = "1";
			caja_trasera_login.style.display = "none";
			formulario_login.style.display = "block";
			contenedor_login_register.style.left = "0px";
			formulario_register.style.display = "none";
		}
	}

	anchoPage();


	function iniciarSesion(){
		if (window.innerWidth > 850){
			formulario_login.style.display = "block";
			contenedor_login_register.style.left = "10px";
			formulario_register.style.display = "none";
			caja_trasera_register.style.opacity = "1";
			caja_trasera_login.style.opacity = "0";
		}else{
			formulario_login.style.display = "block";
			contenedor_login_register.style.left = "0px";
			formulario_register.style.display = "none";
			caja_trasera_register.style.display = "block";
			caja_trasera_login.style.display = "none";
		}
	}

	function register(){
		if (window.innerWidth > 850){
			formulario_register.style.display = "block";
			contenedor_login_register.style.left = "410px";
			formulario_login.style.display = "none";
			caja_trasera_register.style.opacity = "0";
			caja_trasera_login.style.opacity = "1";
		}else{
			formulario_register.style.display = "block";
			contenedor_login_register.style.left = "0px";
			formulario_login.style.display = "none";
			caja_trasera_register.style.display = "none";
			caja_trasera_login.style.display = "block";
			caja_trasera_login.style.opacity = "1";
		}
	}
</script>
<?php $this->stop() ?>
