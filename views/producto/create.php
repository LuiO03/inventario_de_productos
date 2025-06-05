<?php require('views/layouts/header.php'); ?>

<h1 class="text-center">
    Agregar Producto
</h1>
<p class="text-center">
    Aquí puedes agregar un nuevo producto al inventario. Por favor, completa los campos requeridos y haz clic en "Agregar Producto" para guardar la información.
</p>

<form action="<?= BASE_URL ?>producto/insert" method="post" class="d-flex justify-content-center align-items-center flex-wrap flex-column">
    <div class="mb-3 col-10">
        <label for="nombre" class="form-label">Nombre del Producto</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="mb-3 col-10">
        <label for="precio" class="form-label">Precio</label>
        <input type="number" class="form-control" id="precio" name="precio" required>
    </div>
    <div class="mb-3 col-10">
        <label for="cantidad" class="form-label">Cantidad</label>
        <input type="number" class="form-control" id="cantidad" name="cantidad" required>
    </div>
    <button type="submit" class="btn btn-primary col-10 d-flex justify-content-center align-items-center gap-2">
        <i class="ri-add-line fs-3"></i>
        Agregar Producto
    </button>
</form>

<?php require('views/layouts/footer.php'); ?>