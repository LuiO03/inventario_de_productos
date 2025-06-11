<?php headerAdmin(); ?>

<div class="container py-4">
    <h1 class="text-center mb-3">
        <i class="ri-edit-line"></i> Editar Producto
    </h1>
    <p class="text-center text-muted mb-4">
        Aquí puedes editar la información del producto seleccionado. Completa los campos y haz clic en <strong>"Actualizar Producto"</strong>.
    </p>

    <form action="<?= BASE_URL ?>producto/update" method="post" class="row justify-content-center">
        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">

        <div class="mb-3 col-md-6">
            <label for="nombre" class="form-label">Nombre del Producto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" 
                   value="<?= htmlspecialchars($product['nombre']) ?>" required>
        </div>

        <div class="mb-3 col-md-6">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" min="0" class="form-control" id="precio" name="precio" 
                   value="<?= htmlspecialchars($product['precio']) ?>" required>
        </div>

        <div class="mb-4 col-md-6">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" min="0" class="form-control" id="stock" name="stock" 
                   value="<?= htmlspecialchars($product['stock']) ?>" required>
        </div>

        <div class="col-md-6 text-center">
            <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
                <i class="ri-check-line fs-5"></i> Actualizar Producto
            </button>
        </div>
    </form>
</div>

<?php footerAdmin(); ?>
