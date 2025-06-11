<?php headerAdmin(); ?>

<div class="container py-4">
    <h1 class="text-center mb-3">
        <i class="ri-add-line"></i> Agregar Producto
    </h1>
    <p class="text-center text-white mb-4">
        Aquí puedes agregar un nuevo producto al inventario. Completa los campos requeridos y haz clic en <strong>"Agregar Producto"</strong>.
    </p>

    <form action="<?= BASE_URL ?>producto/store" method="post" class="row justify-content-center" autocomplete="off">
        <div class="mb-3 col-md-6">
            <label for="nombre" class="form-label">Nombre del Producto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required autofocus>
        </div>

        <div class="mb-3 col-md-6">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" required>
        </div>

        <div class="mb-4 col-md-12">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" min="0" required>
        </div>

        <div class="col-12 d-flex justify-content-center gap-3">
            <button type="submit" class="btn btn-sm btn-success d-flex align-items-center gap-2">
                <i class="ri-add-line fs-5"></i> Agregar Producto
            </button>
            <a href="<?= BASE_URL ?>producto/index" class="btn btn-sm btn-primary d-flex align-items-center gap-2">
                <i class="ri-arrow-left-line fs-5"></i> Volver
            </a>
        </div>
    </form>
</div>

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

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const flashModal = new bootstrap.Modal(document.getElementById('flashModal'));
            flashModal.show();
        });
    </script>
<?php endif; ?>

<?php footerAdmin(); ?>
