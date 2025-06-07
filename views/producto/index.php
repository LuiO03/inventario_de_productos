<?php 
require('views/layouts/header.php'); ?>

<h1 class="text-center">Lista de Productos</h1>
<p class="text-center">Esta es la página de productos.</p>

<a href="<?= BASE_URL ?>producto/create" class="btn btn-primary d-flex justify-content-center align-items-center gap-2">
    <i class="ri-add-line fs-3"></i>
    Agregar Producto
</a>

<?php if (!empty($mensaje)): ?>
    <!-- Modal de Bootstrap -->
    <div class="modal fade" id="flashModal" tabindex="-1" aria-labelledby="flashModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-<?= htmlspecialchars($mensaje['type']) ?>">
                    <h5 class="modal-title" id="flashModalLabel">Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body bg-dark text-white">
                    <?= htmlspecialchars($mensaje['message']) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para abrir la modal automáticamente -->
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            var flashModal = new bootstrap.Modal(document.getElementById('flashModal'));
            flashModal.show();
        });
    </script>

<?php endif; ?>

<?php require('views/layouts/footer.php'); ?>