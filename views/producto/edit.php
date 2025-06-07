<?php require('views/layouts/header.php'); ?>

<h1>
    Editar Producto
</h1>
<p>
    Aquí puedes editar la información del producto seleccionado. Por favor, completa los campos requeridos y haz clic en "Actualizar Producto" para guardar los cambios.
</p>

<form action="<?= BASE_URL ?>producto/update" method="post" class="d-flex justify-content-center align-items-center flex-wrap flex-column">
    <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
    <div class="mb-3 col-10">
        <label for="nombre" class="form-label">Nombre del Producto</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['nombre']) ?>" required>
    </div>
    <div class="mb-3 col-10">
        <label for="precio" class="form-label">Precio</label>
        <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($product['precio']) ?>" required>
    </div>
    <div class="mb-3 col-10">
        <label for="cantidad" class="form-label">Stock</label>
        <input type="number" class="form-control" id="stock" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary col-10 d-flex justify-content-center align-items-center gap-2">
        <i class="ri-edit-line fs-3"></i>
        Actualizar Producto
    </button>
</form>

<?php require('views/layouts/footer.php'); ?>