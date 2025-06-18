<?php headerAdmin(); ?>

<nav aria-label="breadcrumb" class="breadcrumb-container text-xs">
    <ol class="breadcrumb">
        <li>
            <a href="<?= BASE_URL ?>">
                <i class="ri-home-4-line"></i> Inicio
            </a>
            <i class="ri-arrow-right-s-line separator"></i>
        </li>
        <li>
            <a href="<?= BASE_URL ?>producto">
                <i class="ri-t-shirt-line"></i> Productos
            </a>
            <i class="ri-arrow-right-s-line separator"></i>
        </li>
        <li class="active">
            <i class="ri-edit-box-fill"></i> Editar
        </li>
    </ol>
</nav>

<div class="contenedor-header">
    <h1 class="text-center mb-3">
        Editar Producto
    </h1>
    <p class="text-center mb-4">
        Aquí puedes editar la información del producto seleccionado. Completa los campos y haz clic en <strong>"Actualizar Producto"</strong>.
    </p>
</div>
<div class="contenedor">
    <form action="<?= BASE_URL ?>producto/update/<?=$producto->getId()?>" method="post" class="row justify-content-center" autocomplete="off">
        <input type="hidden" name="id" value="<?= htmlspecialchars($producto->getId()) ?>">
        <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF(); ?>">

        <div class="mb-3 col-md-6">
            <label for="nombre" class="form-label">Nombre del Producto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" 
                   value="<?= htmlspecialchars($producto->getNombre()) ?>" required>
        </div>

        <div class="mb-3 col-md-6">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" min="0" class="form-control" id="precio" name="precio" 
                   value="<?= htmlspecialchars($producto->getPrecio()) ?>" required>
        </div>

        <div class="mb-4 col-md-12">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" min="0" class="form-control" id="stock" name="stock" 
                   value="<?= htmlspecialchars($producto->getStock()) ?>" required>
        </div>

        <div class="col-12 d-flex justify-content-center gap-3">
            <button type="submit" class="btn btn-sm btn-success d-flex align-items-center gap-2">
                <i class="ri-check-line fs-5"></i> Actualizar Producto
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
