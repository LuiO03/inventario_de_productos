<?php
    $entidad = getEntidadDinamica();
?>

<nav aria-label="breadcrumb" class="breadcrumb-container">
    <ol class="breadcrumb">
        <li class="breadcrumb-opcion">
            <a href="<?= BASE_URL ?>">
                <i class="ri-home-heart-fill"></i> Inicio
            </a>
        </li>
        <li class="breadcrumb-opcion">
            <i class="ri-arrow-right-s-line separator"></i>
            <a href="<?= BASE_URL . $entidad['controlador'] ?>">
                <i class="<?= $entidad['icono'] ?>"></i> <?= $entidad['titulo'] ?>
            </a>
        </li>
        <li class=" breadcrumb-opcion">
            <div class="active">
                <?php if ($entidad['metodo'] === 'create'): ?>
                    <i class="ri-arrow-right-s-line separator"></i>
                    <i class="ri-add-box-fill"></i> Crear
                <?php elseif ($entidad['metodo'] === 'edit'): ?>
                    <i class="ri-arrow-right-s-line separator"></i>
                    <i class="ri-edit-circle-fill"></i> Editar
                <?php elseif ($entidad['metodo'] === 'arbol'): ?>
                    <i class="ri-arrow-right-s-line separator"></i>
                    <i class="ri-tree-fill"></i> Arbol
                <?php else: ?>
                    <!--<i class="ri-file-list-3-fill"></i> Lista -->
                <?php endif; ?>
            </div>
        </li>
    </ol>

    <?php if ($entidad['metodo'] === 'index' || $entidad['metodo'] === 'arbol'): ?>
        <a href="<?= BASE_URL . $entidad['controlador'] ?>/create"
           title="Agregar <?= $entidad['titulo'] ?>"
           class="boton btn-primary boton-breadcrumb">
            <span class="boton-icon"><i class="ri-add-circle-fill"></i></span>
            <span class="boton-text">Agregar <?= $entidad['titulo'] ?> </span>
        </a>
    <?php elseif ($entidad['metodo'] === 'create' || $entidad['metodo'] === 'edit'): ?>
        <a href="<?= BASE_URL . $entidad['controlador'] ?>"
           title="Volver a la lista de <?= $entidad['titulo'] ?>"
           class="boton btn-secondary boton-breadcrumb">
            <span class="boton-icon"><i class="ri-arrow-left-s-line"></i></span>
            <span class="boton-text">Volver Atrás</span>
        </a>
    <?php endif; ?>
</nav>
