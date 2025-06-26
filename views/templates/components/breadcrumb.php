<?php
    $datos = getBreadcrumbData();
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
            <a href="<?= BASE_URL . $datos['controlador'] ?>">
                <i class="<?= $datos['icono'] ?>"></i> <?= $datos['titulo'] ?>
            </a>
            <i class="ri-arrow-right-s-line separator"></i>
        </li>
        <li class="active">
            <?php if ($datos['metodo'] === 'create'): ?>
                <i class="ri-add-box-fill"></i> Crear
            <?php elseif ($datos['metodo'] === 'edit'): ?>
                <i class="ri-edit-circle-fill"></i></i> Editar
            <?php else: ?>
                <i class="ri-file-list-3-fill"></i> Lista
            <?php endif; ?>
        </li>
    </ol>

    <?php if ($datos['metodo'] === 'index'): ?>
        <a href="<?= BASE_URL . $datos['controlador'] ?>/create"
           title="Agregar <?= $datos['titulo'] ?>"
           class="d-none d-md-block btn btn-sm btn-primary">
            <span class="boton-icon"><i class="ri-add-circle-fill"></i></span>
            <span class="boton-text">Agregar</span>
        </a>
    <?php endif; ?>
</nav>
