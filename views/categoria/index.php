<?php
    headerAdmin();
    partialBreadcrumb();
?>
<!-- Contenido de la información y botones -->
<div class="contenedor-header">
    <h1>Lista de Categorías</h1>
    <p>Esta es la página de categorías.</p>
    <div class="contenedor-botones">
        <div class="button-borders">
            <button class="btn-export btn-copy" title="Copiar Registros"> <i class="ri-file-copy-line"></i> Copiar </button>
        </div>
        <div class="button-borders">
            <button class="btn-export btn-excel" title="Exportar Excel"> <i class="ri-file-excel-2-line"></i> Excel </button>
        </div>
        <div class="button-borders">
            <button class="btn-export btn-pdf" title="Exportar PDF"> <i class="ri-file-pdf-2-line"></i> PDF </button>
        </div>
        <div class="button-borders">
            <button class="btn-export btn-print" title="Imprimir Tabla"> <i class="ri-printer-line"></i> Imprimir </button>
        </div>
    </div>
</div>
<!-- Contenido de la tabla -->
<div class="contenedor">
    <div class="control-panel">
        <div class="buscador-container">
            <i class="ri-search-eye-line buscador-icon"></i>
            <input type="text" id="buscadorPersonalizado" placeholder="Buscar categorías por nombre o descripción." class="control-buscador">
        </div>
        <div class="selector-container">
            <i class="ri-arrow-down-s-line selector-icon"></i>
            <span>Filas por página</span>
            
            <select id="selectorCantidad" class="control-selector">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="25">25</option>
            </select>
        </div>
    </div>
    <table id="tabla" class="table-sm w-100 tabla-responsive">
        <thead>
            <tr>
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
                        <td class="column-id-td" data-label="ID:"><?= htmlspecialchars($categoria->getId()) ?></td>
                        <td class="text-start" data-label="Nombre:"><?= htmlspecialchars($categoria->getNombre()) ?></td>
                        <td class="text-start" data-label="Descripción:"><?= htmlspecialchars($categoria->getDescripcion() ?: '[Sin descripción]') ?></td>
                        <td class="column-estado-td" data-label="Estado:">
                            <label class="switch-tabla">
                                <input
                                    type="checkbox"
                                    id="switch-categoria-<?= $categoria->getId() ?>"
                                    class="toggle-estado"
                                    data-id="<?= $categoria->getId() ?>"
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