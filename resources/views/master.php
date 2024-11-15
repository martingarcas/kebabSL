<!DOCTYPE html>
<html lang="es">

    <?php require_once 'fragments/head.php';?>

<body>

    <?php require_once 'menu.php';?>

    <?= $this->section('header')?>
    <?= $this->section('listado')?>
    <?= $this->section('formulario')?>
    <?= $this->section('seccion-prueba')?>
	<?php require_once 'fragments/footer.php';?>
    <?= $this->section('footer')?>

	<?php require_once 'scripts.php';?>
	<?= $this->section('scripts')?>

</body>
</html>

