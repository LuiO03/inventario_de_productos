<?php 
    headerAdmin();
    partialBreadcrumb();
?>

<div class="contenedor-header">
    <h1 class="text-center mb-3">
        Agregar Producto
    </h1>
    <p class="text-center mb-4">
        Aquí puedes agregar un nuevo producto al inventario. Completa los campos requeridos y haz clic en <strong>"Agregar Producto"</strong>.
    </p>
</div>

<div class="contenedor">
    <form action="<?= BASE_URL ?>producto/store" method="post" class="row justify-content-center" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF(); ?>">

        <div class="mb-3 col-md-6">
            <label for="nombre" class="form-label">Nombre del Producto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required autofocus placeholder="Ingrese el nombre del producto">
        </div>

        <div class="mb-3 col-md-6">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" value="34" required>
        </div>

        <div class="mb-4 col-md-12">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" min="0" value="12" required>
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

<?php 
    modalFlash($mensaje);
    footerAdmin(); 
?>
