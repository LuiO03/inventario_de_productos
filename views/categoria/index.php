<?php
headerAdmin();
partialBreadcrumb();
?>
<!-- Contenido de la información y botones -->
<div class="contenedor-header index">
    <h1>Lista de Categorías</h1>
    <p>Esta es la página de categorías.</p>
</div>
<div class="contenedor-botones">
    <div class="botones-export">
        <form class="formExportarPdf" id="formExportarPdf" action="<?= BASE_URL ?>categoria/exportarPdf" method="POST" target="_blank">
            <input type="hidden" name="ids" id="idsSeleccionadosPdf">
            <button type="submit" class="btn-export btn-pdf">
                <i class="ri-file-pdf-2-line"></i>
                <span class="export-text">PDF</span>
            </button>
        </form>
        <form class="formExportarExcel" id="formExportarExcel" action="<?= BASE_URL ?>categoria/exportarExcel" method="POST">
            <input type="hidden" name="ids" id="idsSeleccionadosExcel">
            <button type="submit" class="btn-export btn-excel">
                <i class="ri-file-excel-2-line"></i>
                <span class="export-text">Excel</span>
            </button>
        </form>
        <button class="btn-export btn-danger eliminar-seleccion" id="btnEliminarSeleccionados">
            <i class="ri-delete-bin-7-line"></i>
            <span class="export-text">Eliminar</span>
        </button>
        <button class="btn-export btn-secondary cancelar-seleccion" id="btnCancelarSeleccion">
            <i class="ri-close-circle-line"></i>
            <span class="export-text">Cancelar</span>
        </button>
    </div>
</div>
<!-- Contenido de la tabla -->
<div class="control-panel">
    <div class="buscador-container">
        <i class="ri-search-eye-line buscador-icon"></i>
        <input type="text" id="buscadorPersonalizado" placeholder="Buscar categorías por nombre o descripción." class="control-buscador">
    </div>
    <div class="selector">
        <span>Filas por página</span>
        <div class="selector-input">
            <i class="ri-arrow-down-s-line selector-icon"></i>
            <select id="selectorCantidad" class="control-selector">
                <option value="5">5</option>
                <option value="10" selected data-default="true">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>     
        </div>
    </div>
    <div class="selector">
        <span>Filtrar estado</span>
        <div class="selector-input">
            <i class="ri-filter-line selector-icon"></i>
            <select id="filtroEstado" class="control-selector">
                <option value="">Todos</option>
                <option value="Visible">Habilitado</option>
                <option value="Oculto">Deshabilitado</option>
            </select>
        </div>
    </div>
    <div class="selector">
        <span>Ordenar por</span>
        <div class="selector-input">
            <i class="ri-sort-desc selector-icon"></i>
            <select id="filtroOrden" class="control-selector">
                <option value="">Ninguno</option>
                <option value="nombre_asc">A–Z</option>
                <option value="nombre_desc">Z–A</option>
                <option value="fecha_desc">Más recientes</option>
                <option value="fecha_asc">Más antiguos</option>
            </select>
        </div>
    </div>
</div>

<div class="contenedor">
    <table id="tabla" class="table-sm w-100 tabla-responsive">
        <thead>
            <tr>
                <th class="column-check-th"><input type="checkbox" id="checkAll"></th>
                <th class="column-id-th">ID</th>
                <th class="column-name-th">Nombre</th>
                <th class="column-descripcion-th">Descripción</th>
                <th class="column-estado-th">Estado</th>
                <th class="column-fecha-th">Fecha</th>
                <th class="column-opciones-th">Opciones</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td class="column-check-td"><input type="checkbox" class="check-row" value="<?= $categoria->getId() ?>"></td>
                        <td class="column-id-td" data-label="ID:"><?= htmlspecialchars($categoria->getId()) ?></td>
                        <td class="column-name-td" data-label="Nombre:">
                            <div>
                                <?= htmlspecialchars($categoria->getNombre()) ?><strong class="column-mbdata-td"> - #<?= htmlspecialchars($categoria->getId()) ?></strong>
                            </div>
                        </td>
                        <td class="column-descripcion-td" data-label="Descripción:"><?= htmlspecialchars($categoria->getDescripcion() ?: '[Sin descripción]') ?></td>
                        <td class="column-estado-td" data-label="Estado:">
                            <span class="estado-texto d-print-inline"><?= $categoria->getEstado() ? 'Visible' : 'Oculto' ?></span>
                            <label class="switch-tabla">
                                <input type="checkbox" id="switch-categoria-<?= $categoria->getId() ?>" class="toggle-estado" data-id="<?= $categoria->getId() ?>"
                                    <?= $categoria->getEstado() ? 'checked' : '' ?> name="estado">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td class="column-fecha-td"><?= htmlspecialchars(FechaHelper::formatoCorto($categoria->getCreatedAt())) ?></td>
                        <td class="column-opciones-td">
                            <div class="table-botones">
                                <button class="btn-ver-categoria btn btn-sm btn-info"
                                    data-id="<?= $categoria->getId() ?>"
                                    title="Ver Categoría">
                                    <span class="boton-text">Ver</span>
                                    <span class="boton-icon"><i class="ri-eye-2-fill"></i></span>
                                </button>
                                <a href="<?= BASE_URL ?>categoria/edit/<?= urlencode($categoria->getSlug()) ?>" title="Editar Categoría" class="btn btn-sm btn-warning">
                                    <span class="boton-icon"><i class="ri-edit-circle-fill"></i></span>
                                    <span class="boton-text">Editar</span>
                                </a>
                                <button type="button" title="Eliminar Categoría" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $categoria->getId() ?>">
                                    <span class="boton-text">Borrar</span>
                                    <span class="boton-icon"><i class="ri-delete-bin-2-fill"></i></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
document.querySelectorAll('.selector-input').forEach(wrapper => {
  const select = wrapper.querySelector('.control-selector');
  const icon   = wrapper.querySelector('.selector-icon');

  function toggleActive() {
    const selectedOption = select.options[select.selectedIndex];
    const isDefault = selectedOption.hasAttribute("data-default");

    if (select.value && !isDefault) {
      select.classList.add('active');
      icon.classList.add('active');
    } else {
      select.classList.remove('active');
      icon.classList.remove('active');
    }
  }

  select.addEventListener('change', toggleActive);
  toggleActive(); // al cargar
});

</script>


<?php
    getModal("modal-categoria");
    menuFlotante();
    modalFlash($mensaje);
    modalConfirmacion();
    footerAdmin();
?>