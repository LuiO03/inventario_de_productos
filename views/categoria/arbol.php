<?php
headerAdmin();
partialBreadcrumb();

function mostrarCategorias($parentId, $agrupadas, $nivel = 1)
{
    if (!isset($agrupadas[$parentId])) return;

    echo '<ul class="ul-nivel' . $nivel . '">';
    foreach ($agrupadas[$parentId] as $cat) {
        $tieneHijos = isset($agrupadas[$cat->getId()]);
        $clase = $cat->getEstado() ? 'text-primary' : 'text-muted';

        echo "<li class='li-nivel{$nivel} $clase'>";
        if ($tieneHijos) {
            echo "<div class='toggle-nodo'>
                    <div class='toggle-control parent' data-target-id='{$cat->getId()}'>
                        <span class='flecha'></span>
                        <small class='toggle-id'>(ID: {$cat->getId()})</small>
                        <span class='categoria-nombre'>{$cat->getNombre()}</span>
                    </div> 
                    <div class='toggle-acciones'>
                        <button class='btn-ver-categoria boton btn-info' data-id='{$cat->getId()}' title='Ver Categoría'>
                            <span class='boton-text'>Ver</span>
                            <span class='boton-icon'><i class='ri-eye-2-fill'></i></span>
                        </button>
                        <a href='" . BASE_URL . "categoria/edit/" . urlencode($cat->getSlug()) . "' class='boton btn-warning' title='Editar Categoría'>
                            <span class='boton-text'>Editar</span>
                            <span class='boton-icon'><i class='ri-edit-circle-fill'></i></span>
                        </a>
                        <button class='boton btn-danger btn-eliminar' data-bs-toggle='modal' data-bs-target='#deleteModal' data-id='{$cat->getId()}' title='Eliminar Categoría'>
                            <span class='boton-text'>Borrar</span>
                            <span class='boton-icon'><i class='ri-delete-bin-2-fill'></i></span>
                        </button>
                        <a href='" . BASE_URL . "categoria/create/" . urlencode($cat->getSlug() ?: $cat->getId()) . "' class='boton btn-success' title='Agregar Subcategoría'>
                            <span class='boton-text'>Agregar</span>
                            <span class='boton-icon'><i class='ri-add-circle-fill'></i></span>
                        </a>
                    </div>
                </div>";
            echo "<div class='hijos' data-parent-id='{$cat->getId()}'>";
            mostrarCategorias($cat->getId(), $agrupadas, $nivel + 1);
            echo "</div>";
        } else {
            echo "<div class='toggle-nodo'>
                    <div class='toggle-control'>
                        <span class='sin-flecha'></span>
                        <small class='toggle-id'>(ID: {$cat->getId()})</small>
                        <span class='categoria-nombre'>{$cat->getNombre()}</span>
                    </div> 
                    <div class='toggle-acciones'>
                        <button class='btn-ver-categoria boton btn-info' data-id='{$cat->getId()}' title='Ver Categoría'>
                            <span class='boton-text'>Ver</span>
                            <span class='boton-icon'><i class='ri-eye-2-fill'></i></span>
                        </button>
                        <a href='" . BASE_URL . "categoria/edit/" . urlencode($cat->getSlug()) . "' class='boton btn-warning' title='Editar Categoría'>
                            <span class='boton-text'>Editar</span>
                            <span class='boton-icon'><i class='ri-edit-circle-fill'></i></span>
                        </a>
                        <button class='boton btn-danger btn-eliminar' data-bs-toggle='modal' data-bs-target='#deleteModal' data-id='{$cat->getId()}' title='Eliminar Categoría'>
                            <span class='boton-text'>Borrar</span>
                            <span class='boton-icon'><i class='ri-delete-bin-2-fill'></i></span>
                        </button>
                        <a href='" . BASE_URL . "categoria/create/" . urlencode($cat->getSlug() ?: $cat->getId()) . "' class='boton btn-success' title='Agregar Subcategoría'>
                            <span class='boton-text'>Agregar</span>
                            <span class='boton-icon'><i class='ri-add-circle-fill'></i></span>
                        </a>
                    </div>
                </div>";
        }

        echo '</li>';
    }
    echo '</ul>';
}
?>

<!-- Contenido de la información y botones -->
<div class="contenedor-header">
    <h1>Árbol de Categorías</h1>
    <p>Visualiza la jerarquía de categorías y subcategorías.</p>
</div>
<div class="contenedor-botones">
    <div class="botones-export">
        <form id="formExportarPdf" action="<?= BASE_URL ?>marca/exportarPdf" method="POST" target="_blank" style="display:inline;">
            <input type="hidden" name="ids" id="idsSeleccionadosPdf">
            <button type="submit" class="btn-export btn-pdf">
                <i class="ri-file-pdf-2-line"></i>
                <span class="export-text">PDF</span>
            </button>
        </form>
        <form id="formExportarExcel" action="<?= BASE_URL ?>marca/exportarExcel" method="POST" style="display:inline;">
            <input type="hidden" name="ids" id="idsSeleccionadosExcel">
            <button type="submit" class="btn-export btn-excel">
                <i class="ri-file-excel-2-line"></i>
                <span class="export-text">Excel</span>
            </button>
        </form>
        <button class="btn-export btn-primary eliminar-seleccion" id="btnEliminarSeleccionados">
            <i class="ri-delete-bin-7-line"></i>
            <span class="export-text">Eliminar</span>
        </button>
        <button class="btn-export btn-secondary cancelar-seleccion" id="btnCancelarSeleccion">
            <i class="ri-close-circle-line"></i>
            <span class="export-text">Cancelar</span>
        </button>
    </div>
</div>

<section class="categoria-arbol card p-3">
    <?php mostrarCategorias(null, $agrupadas); ?>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const controles = document.querySelectorAll('.toggle-control');

    controles.forEach(control => {
        control.addEventListener('click', () => {
            const targetId = control.getAttribute('data-target-id');
            if (!targetId) return;

            const hijos = document.querySelector(`.hijos[data-parent-id='${targetId}']`);
            if (!hijos) return;

            control.classList.toggle('expandida');
            hijos.classList.toggle('activa');
        });
    });
});
</script>

<?php
    getModal("modal-categoria");
    menuFlotante();
    modalConfirmacion();
    footerAdmin();
?>
