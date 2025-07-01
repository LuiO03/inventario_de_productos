<?php 
    headerAdmin();
    partialBreadcrumb();
?>

<div class="contenedor-header">
    <h1 class="text-center">
        Agregar Categoría
    </h1>
    <p class="text-center">
        Aquí puedes agregar una nueva categoría para los productos.
    </p>
</div>

<form action="<?= BASE_URL ?>categoria/store" method="post" class="formulario" autocomplete="off">
    <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF(); ?>">

    <!-- Nombre -->
    <div class="input-group">
        <label for="nombre" class="form-label">Nombre de la Categoría</label>
        <div class="input-icono">
            <i class="ri-price-tag-3-line"></i>
            <input type="text" id="nombre" name="nombre" required placeholder="Ingrese el nombre">
        </div>
    </div>

    <!-- Estado -->
    <div class="input-group">
        <label class="form-label">Estado</label>
        <div class="checkbox">
            <input type="checkbox" id="estado" name="estado" checked>
            <label for="estado">Activo</label>
        </div>
    </div>

    <!-- Descripción -->
    <div class="input-group w-100">
        <label for="descripcion" class="form-label">Descripción</label>
        <div class="input-icono">
            <i class="ri-file-text-line"></i>
            <textarea id="descripcion" name="descripcion" rows="3" placeholder="Describe brevemente..."></textarea>
        </div>
    </div>

    <!-- Botones -->
    <div class="acciones">
        <button type="submit" class="btn-success">
            <i class="ri-add-line"></i> Agregar Categoría
        </button>
        <a href="<?= BASE_URL ?>categoria/index" class="btn-primary">
            <i class="ri-arrow-left-line"></i> Volver
        </a>
    </div>
</form>

<?php 
    modalFlash($mensaje);
    footerAdmin(); 
?>
