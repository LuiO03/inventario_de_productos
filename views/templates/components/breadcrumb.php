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
        <li class="breadcrumb-opcion">
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
                <?php elseif ($entidad['metodo'] === 'permisos'): ?>
                    <i class="ri-arrow-right-s-line separator"></i>
                    <i class="ri-key-fill"></i> Permisos
                <?php endif; ?>
            </div>
        </li>
    </ol>

    <div class="breadcrumb-botones">
        <div class="breadcrumb-botones">
            <?php if ($entidad['metodo'] === 'index' || $entidad['metodo'] === 'arbol'): ?>
                <a href="<?= BASE_URL . $entidad['controlador'] ?>/create"
                title="Agregar <?= $entidad['titulo'] ?>"
                class="boton btn-primary boton-breadcrumb">
                    <span class="boton-icon"><i class="ri-add-circle-fill"></i></span>
                    <span class="boton-text">Agregar <?= $entidad['titulo'] ?> </span>
                </a>
            <?php elseif ($entidad['metodo'] === 'create' || $entidad['metodo'] === 'edit' || $entidad['metodo'] === 'permisos'): ?>
                <a href="<?= BASE_URL . $entidad['controlador'] ?>"
                title="Volver a la lista de <?= $entidad['titulo'] ?>"
                class="boton btn-secondary boton-breadcrumb">
                    <span class="boton-icon"><i class="ri-arrow-left-s-line"></i></span>
                    <span class="boton-text">Volver Atrás</span>
                </a>
            <?php endif; ?>
        </div>
</nav>

<style>
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: var(--color-sidebar);
    width: 100%;
    box-shadow: var(--sombra-material);
    z-index: 1;
    border-radius: 6px;
    overflow: hidden;
    top: 120%;
    right: 0px;
}

.dropdown.show .dropdown-content {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 10px;
}

</style>
<script>
document.getElementById("exportarTodoBtn").addEventListener("click", function() {
    this.parentElement.classList.toggle("show");
});

// Cerrar si se hace clic fuera
document.addEventListener("click", function(e) {
    const dropdown = document.querySelector(".dropdown");
    if (!dropdown.contains(e.target)) {
        dropdown.classList.remove("show");
    }
});
</script>