<?php
    $entidad = getEntidadDinamica();
?>

<nav aria-label="breadcrumb" class="breadcrumb-container">
    <ol class="breadcrumb">
        <li>
            <a href="<?= BASE_URL ?>">
                <i class="ri-home-heart-fill"></i> Inicio
            </a>
            <i class="ri-arrow-right-s-line separator"></i>
        </li>
        <li>
            <a href="<?= BASE_URL . $entidad['controlador'] ?>">
                <i class="<?= $entidad['icono'] ?>"></i> <?= $entidad['titulo'] ?>
            </a>
            <i class="ri-arrow-right-s-line separator"></i>
        </li>
        <li class="active">
            <?php if ($entidad['metodo'] === 'create'): ?>
                <i class="ri-add-box-fill"></i> Crear
            <?php elseif ($entidad['metodo'] === 'edit'): ?>
                <i class="ri-edit-circle-fill"></i></i> Editar
            <?php else: ?>
                <i class="ri-file-list-3-fill"></i> Lista
            <?php endif; ?>
        </li>
    </ol>

    <?php if ($entidad['metodo'] === 'index'): ?>
        <a href="<?= BASE_URL . $entidad['controlador'] ?>/create"
           title="Agregar <?= $entidad['titulo'] ?>"
           class="d-none d-md-block btn btn-sm btn-primary">
            <span class="boton-icon"><i class="ri-add-circle-fill"></i></span>
            <span class="boton-text">Agregar</span>
        </a>
    <?php endif; ?>
</nav>
