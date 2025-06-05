<?php require('views/layouts/header.php'); ?>

<h1>Barra Lateral con Bootstrap 5</h1>
<?php if (!empty($mensaje)): ?>
    <div class="alert alert-<?= $mensaje['type'] ?>">
        <?= htmlspecialchars($mensaje['message']) ?>
    </div>
<?php endif; ?>

<?php require('views/layouts/footer.php'); ?>