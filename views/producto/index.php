<?php headerAdmin(); ?>

<h1 class="text-center">Lista de Productos</h1>
<p class="text-center">Esta es la página de productos.</p>



<div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
    <a href="<?= BASE_URL ?>producto/create" class="btn btn-sm btn-success d-flex align-items-center gap-2">
        <i class="ri-add-line fs-5"></i> Agregar Producto
    </a>
    <a href="<?= BASE_URL ?>" class="btn btn-sm btn-primary d-flex align-items-center gap-2">
        <i class="ri-arrow-left-line fs-5"></i> Volver
    </a>
</div>

<table class="table table-sm table-striped table-bordered mt-4 table-dark">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= htmlspecialchars($producto['id']) ?></td>
                    <td><?= htmlspecialchars($producto['nombre']) ?></td>
                    <td><?= htmlspecialchars($producto['precio']) ?></td>
                    <td><?= htmlspecialchars($producto['stock']) ?></td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="<?= BASE_URL ?>producto/edit/<?= $producto['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="ri-edit-line"></i> Editar
                            </a>
                            <form method="POST" action="<?= BASE_URL ?>producto/delete/<?= $producto['id'] ?>" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="ri-delete-bin-line"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center">No hay productos registrados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php if (!empty($mensaje)): ?>
    <!-- Modal de Bootstrap -->
    <div class="modal fade" id="flashModal" tabindex="-1" aria-labelledby="flashModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-<?= htmlspecialchars($mensaje['type']) ?>">
                    <h6 class="modal-title" id="flashModalLabel">Mensaje</h6>
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