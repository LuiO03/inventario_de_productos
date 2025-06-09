<?php headerAdmin(); ?>

<h1 class="text-center">
    Agregar Producto
</h1>
<p class="text-center">
    Aquí puedes agregar un nuevo producto al inventario. Por favor, completa los campos requeridos y haz clic en "Agregar Producto" para guardar la información.
</p>

<form action="<?= BASE_URL ?>producto/store" method="post" class="d-flex justify-content-center align-items-center flex-wrap flex-column" autocomplete="off">
    <div class="mb-3 col-10">
        <label for="nombre" class="form-label">Nombre del Producto</label>
        <input type="text" class="form-control" id="nombre" name="nombre" autofocus>
    </div>
    <div class="mb-3 col-10">
        <label for="precio" class="form-label">Precio</label>
        <input type="number" class="form-control" id="precio" name="precio" required>
    </div>
    <div class="mb-3 col-10">
        <label for="cantidad" class="form-label">Stock</label>
        <input type="number" class="form-control" id="stock" name="stock" required>
    </div>

    <div class="d-flex justify-content-center align-items-center flex-wrap gap-3">
        <button type="submit" class="btn btn-sm btn-primary d-flex justify-content-center align-items-center gap-2">
            <i class="ri-add-line fs-3"></i>
            Agregar Producto
        </button>
        <a href="<?= BASE_URL ?>producto/index" class="btn btn-sm btn-secondary d-flex justify-content-center align-items-center gap-2">
            <i class="ri-arrow-left-line fs-3"></i>
            Volver
        </a>
    </div>
</form>


<?php if (!empty($mensaje)): ?>
    <!-- Modal de Bootstrap -->
    <div class="modal fade" id="flashModal" tabindex="-1" aria-labelledby="flashModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-<?= htmlspecialchars($mensaje['type']) ?>">
                    <h6 class="modal-title" id="flashModalLabel"><?= htmlspecialchars($mensaje['header']) ?></h6>
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

<?php footerAdmin(); ?>