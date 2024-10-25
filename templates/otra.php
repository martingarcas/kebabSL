<?php $this->layout('layout');?>

<h1>Lista de Usuarios</h1>
<ul>
    <?php foreach ($users as $user): ?>
        <li><?= $user['name']; ?> - <?= $user['email']; ?></li>
    <?php endforeach; ?>
</ul>