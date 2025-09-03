<?php 
    headerAdmin();
    partialBreadcrumb();
    $entidad = getEntidadDinamica();
?>
<div class="contenedor-header index">
    <h1>Lista de Roles</h1>
    <p>Gestión de roles del sistema.</p>
</div>
<div class="contenedor-botones">
    <div class="botones-export">
        <form class="formExportarPdf" id="formExportarPdf" action="<?= BASE_URL ?>rol/exportarPdf" method="POST" target="_blank">
            <input type="hidden" name="ids" id="idsSeleccionadosPdf">
            <button type="submit" class="btn-export btn-pdf">
                <i class="ri-file-pdf-2-line"></i>
                <span class="export-text">PDF</span>
            </button>
        </form>
        <form class="formExportarExcel" id="formExportarExcel" action="<?= BASE_URL ?>rol/exportarExcel" method="POST">
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
<div class="control-panel">
    <div class="buscador-container">
        <i class="ri-search-eye-line buscador-icon"></i>
        <input type="text" id="buscadorPersonalizado" placeholder="Buscar rol por nombre o descripción..." class="control-buscador" name="buscador">
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
                <th class="column-id-th">ID</th>
                <th class="column-name-th">Nombre</th>
                <th class="column-descripcion-th">Descripción</th>
                <th class="column-autor-th">Autor</th>
                <th class="column-fecha-th">Fecha</th>
                <th class="column-user-th">Usuarios</th>
                <th class="column-estado-th">Estado</th>
                <th class="column-opciones-sm-th four-options">Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($roles)): ?>
                <?php foreach ($roles as $rol): ?>
                    <tr>
                        <td class="column-id-td" data-label="ID:"><?= htmlspecialchars($rol->getId()) ?></td>
                        <td class="column-name-td" data-label="Nombre:">
                            <div>
                                <?= htmlspecialchars($rol->getNombre()) ?><strong class="column-mbdata-td"> - #<?= htmlspecialchars($rol->getId()) ?></strong>
                            </div>
                        </td>
                        <td class="column-descripcion-td" data-label="Descripción:"><?= htmlspecialchars($rol->getDescripcion() ?: '[Sin descripción]') ?></td>
                        <td class="column-autor-td" data-label="Creado por:"><?= htmlspecialchars($rol->getNombreCreador() ?: 'Desconocido') ?></td>
                        <td class="column-fecha-td" data-label="Creado el:"><?= htmlspecialchars($rol->getCreatedAt() ? FechaHelper::formatoCorto($rol->getCreatedAt()) : 'Desconocido') ?></td>
                        <td class="column-user-td" data-label="Usuarios:" class="text-center"><?= $rol->getUsuariosCount() ?? 0 ?></td>
                        <td class="column-estado-td" data-label="Estado:">
                            <span class="estado-texto d-print-inline"><?= $rol->getEstado() ? 'Visible' : 'Oculto' ?></span>
                            <label class="switch-tabla">
                                <input type="checkbox" id="switch-marca-<?= $rol->getId() ?>" class="toggle-estado" data-id="<?= $rol->getId() ?>"
                                    <?= $rol->getEstado() ? 'checked' : '' ?> name="estado">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td class="column-opciones-sm-td four-options">
                            <div class="table-botones">
                                <button class="btn-ver boton btn-info" data-id="<?= $rol->getId() ?>" title="Ver detalles">
                                    <span class="boton-text">Ver</span>
                                    <span class="boton-icon"><i class="ri-eye-2-fill"></i></span>
                                </button>
                                <a href="<?= BASE_URL ?>rol/edit/<?= urlencode($rol->getId()) ?>" class="boton btn-warning" title="Editar rol">
                                    <span class="boton-text">Editar</span>
                                    <span class="boton-icon"><i class="ri-edit-circle-fill"></i></span>
                                </a>
                                <button type="button" class="boton btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $rol->getId() ?>" title="Eliminar rol">
                                    <span class="boton-text">Eliminar</span>
                                    <span class="boton-icon"><i class="ri-delete-bin-2-fill"></i></span>
                                </button>
                                <a href="<?= BASE_URL ?>rol/permisos/<?= $rol->getId(); ?>" class="boton btn-secondary" title="Gestionar permisos">
                                    <span class="boton-text">Permisos</span>
                                    <span class="boton-icon"><i class="ri-key-2-fill"></i></span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Detalles Rol -->
<div class="modal fade" id="viewRolModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h6 class="modal-title">Detalles del Rol</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <h2 id="modal-tabla-titulo" class="modal-nombre"></h2>
        <table class="table-sm w-100">
          <tr><td class="fw-bolder px-2">ID:</td><td class="text-start px-2" id="modal-id"></td></tr>
          <tr><td class="fw-bolder px-2">Nombre:</td><td class="text-start px-2" id="modal-nombre"></td></tr>
          <tr><td class="fw-bolder px-2">Descripción:</td><td class="text-start px-2" id="modal-descripcion"></td></tr>
          <tr><td class="fw-bolder px-2">N° Usuarios:</td><td class="text-start px-2" id="modal-user"></td></tr>
          <tr><td class="fw-bolder px-2">Estado:</td><td class="text-start px-2" id="modal-estado"></td></tr>
          <tr>
            <td class="text-start fw-bolder px-2">Creado por:</td>
            <td class="text-start px-2">
                <span id="modal-creado-por"></span>
                <div>
                    <i class="ri-calendar-schedule-fill text-primario"></i>
                    <span class="text-primario" id="modal-created-at"></span>
                </div>
            </td>
        </tr>
        <tr>
            <td class="text-start fw-bolder px-2">Modificado por:</td>
            <td class="text-start px-2">
                <span id="modal-modificado-por"></span>
                <div>
                    <i class="ri-calendar-schedule-fill text-primario"></i>
                    <span class="text-primario" id="modal-updated-at"></span>
                </div>
            </td>
        </tr>
        </table>
      </div>

      <div class="modal-footer border-0 justify-content-center">
        <button type="button" class="boton bg-modal-close" data-bs-dismiss="modal">
            <span class="boton-icon text-base-inverted"><i class="ri-close-line"></i></span>
            <span class="boton-text">Cerrar</span>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-ver').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`<?= BASE_URL ?>rol/show/${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                const titulo = `${data.nombre}`;
                document.getElementById('modal-tabla-titulo').textContent = titulo || '-';
                document.getElementById('modal-id').textContent = data.id ?? '-';
                document.getElementById('modal-nombre').textContent = data.nombre ?? '-';
                document.getElementById('modal-descripcion').textContent = data.descripcion ?? 'Sin descripción';
                document.getElementById('modal-user').textContent = data.usuarios_count ?? '0';
                document.getElementById('modal-estado').innerHTML = data.estado 
                    ? '<span class="medalla bg-success"><i class="ri-eye-line"></i> Habilitado</span>'
                    : '<span class="medalla bg-secondary"><i class="ri-eye-off-line"></i> Deshabilitado</span>';
                document.getElementById('modal-creado-por').textContent = data.creado_por ?? '-';
                document.getElementById('modal-modificado-por').textContent = data.modificado_por ?? '-';
                document.getElementById('modal-created-at').textContent = data.created_at || '-';
                document.getElementById('modal-updated-at').textContent = data.updated_at || '-';;

                new bootstrap.Modal(document.getElementById('viewRolModal')).show();
            });
        });
    });
});
</script>

<?php
    menuFlotante();
    modalFlash($mensaje);
    modalConfirmacion();
    footerAdmin();
?>
