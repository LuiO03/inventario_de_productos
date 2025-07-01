<?php 
    headerAdmin();
    partialBreadcrumb();
?>

<div class="contenedor-header">
    <h1 class="text-center">
        Editar Categoría
    </h1>
    <p class="text-center">
        Aquí puedes editar la información de la categoría seleccionada.
    </p>
</div>

<div class="contenedor">
    <form action="<?= BASE_URL ?>categoria/update/<?= $categoria->getId() ?>" method="post" class="row justify-content-center" autocomplete="off">
        <input type="hidden" name="id" value="<?= htmlspecialchars($categoria->getId()) ?>">
        <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF(); ?>">

        <div class="mb-3 col-md-6">
            <label for="nombre" class="form-label">Nombre de la Categoría</label>
            <input type="text" class="form-control" id="nombre" name="nombre" 
                   value="<?= htmlspecialchars($categoria->getNombre()) ?>" required autofocus>
        </div>

        <div class="mb-3 col-md-6">
            <label for="estado" class="form-label">Estado</label>
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="estado" name="estado" <?= $categoria->getEstado() ? 'checked' : '' ?>>
                <label class="form-check-label" for="estado">
                    Activo
                </label>
            </div>
        </div>

        <div class="mb-4 col-md-12">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Describe brevemente la categoría"><?= htmlspecialchars($categoria->getDescripcion()) ?></textarea>
        </div>

        <div class="col-12 d-flex justify-content-center gap-3">
            <button type="submit" class="btn btn-sm btn-success d-flex align-items-center gap-2">
                <i class="ri-check-line fs-5"></i> Actualizar Categoría
            </button>
            <a href="<?= BASE_URL ?>categoria/index" class="btn btn-sm btn-primary d-flex align-items-center gap-2">
                <i class="ri-arrow-left-line fs-5"></i> Volver
            </a>
        </div>
    </form>
</div>

<?php
    modalFlash($mensaje);
    footerAdmin(); 
?>
