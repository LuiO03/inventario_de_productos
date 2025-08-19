<?php
headerAdmin();
partialBreadcrumb();
?>
<!-- Contenido de la información y botones -->
<div class="contenedor-header">
    <h1>Lista de Categorías</h1>
    <p>Esta es la página de categorías.</p>
</div>
<!-- Contenido de la tabla -->
<div class="control-panel">
    <div class="buscador-container">
        <i class="ri-search-eye-line buscador-icon"></i>
        <input type="text" id="buscadorPersonalizado" placeholder="Buscar categorías por nombre o descripción." class="control-buscador">
    </div>
    <div class="selector-container">
        <i class="ri-arrow-down-s-line selector-icon"></i>
        <span>Filas por página</span>
        
        <select id="selectorCantidad" class="control-selector">
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
    <div class="selector-container">
        <i class="ri-filter-line selector-icon"></i>
        <span>Filtrar Estado</span>
        <select id="filtroEstado" class="control-selector">
            <option value="">Todos</option>
            <option value="Visible">Habilitado</option>
            <option value="Oculto">Deshabilitado</option>
        </select>
    </div>
</div>
<div class="contenedor">
    <div class="contenedor-botones">
        <div class="botones-export">
            <form id="formExportarPdf" action="<?= BASE_URL ?>categoria/exportarPdf" method="POST" target="_blank" style="display:inline;">
                <input type="hidden" name="ids" id="idsSeleccionadosPdf">
                <button type="submit" class="btn-export btn-pdf">
                    <i class="ri-file-pdf-2-line"></i>
                    <span class="export-text">PDF</span>
                </button>
            </form>
            <form id="formExportarExcel" action="<?= BASE_URL ?>categoria/exportarExcel" method="POST" style="display:inline;">
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
    <table id="tabla" class="table-sm w-100 tabla-responsive">
        <thead>
            <tr>
                <th class="column-check-th"><input type="checkbox" id="checkAll"></th>
                <th class="column-id-th">ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th class="column-estado-th">Estado</th>
                <th class="column-opciones-th">Opciones</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td class="column-check-td"><input type="checkbox" class="check-row" value="<?= $categoria->getId() ?>"></td>
                        <td class="column-id-td text-center" data-label="ID:"><?= htmlspecialchars($categoria->getId()) ?></td>
                        <td class="text-start" data-label="Nombre:"><?= htmlspecialchars($categoria->getNombre()) ?></td>
                        <td class="text-start" data-label="Descripción:"><?= htmlspecialchars($categoria->getDescripcion() ?: '[Sin descripción]') ?></td>
                        <td class="column-estado-td text-center" data-label="Estado:">
                            <span class="estado-texto d-print-inline"><?= $categoria->getEstado() ? 'Visible' : 'Oculto' ?></span>
                            <label class="switch-tabla">
                                <input type="checkbox" id="switch-categoria-<?= $categoria->getId() ?>" class="toggle-estado" data-id="<?= $categoria->getId() ?>"
                                    <?= $categoria->getEstado() ? 'checked' : '' ?> name="estado">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td class="column-opciones-td">
                            <div class="table-botones">
                                <button
                                    class="btn-ver-categoria btn btn-sm btn-info"
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

<?php
getModal("modal-categoria");
menuFlotante();
modalFlash($mensaje);
modalConfirmacion();
footerAdmin();
?>