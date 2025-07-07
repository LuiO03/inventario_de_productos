<?php 
if (Flash::hasValidate()):
    $data = Flash::getValidate();
    $tipo = $data['type'] ?? 'danger';
    $mensajes = $data['mensajes'] ?? [];

    $iconos = [
        'danger' => 'ri-error-warning-fill',
        'warning' => 'ri-alert-fill',
        'info' => 'ri-information-fill',
        'success' => 'ri-checkbox-circle-fill',
        'secondary' => 'ri-information-line'
    ];
    $icono = $iconos[$tipo] ?? $iconos['danger'];
?>
<div class="alert-validate alert-<?= $tipo ?>" role="alert">
    <div class="alert-icon">
        <i class="<?= $icono ?>"></i>
    </div>
    <div class="alert-title">
        <?php if (count($mensajes) === 1): ?>
            <p><?= $mensajes[0] ?></p>
        <?php else: ?>
            <ul class="mb-0">
                <?php foreach ($mensajes as $msg): ?>
                    <li><i class="ri-arrow-right-double-fill"></i> <?= $msg ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <div class="alert-close" title="Cerrar">
        <i class="ri-close-fill"></i>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const closeBtn = document.querySelector('.alert-validate .alert-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            closeBtn.closest('.alert-validate').style.display = 'none';
        });
    }
});
</script>
<?php endif; ?>
