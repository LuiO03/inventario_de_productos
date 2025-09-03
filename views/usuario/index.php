<?php 
    headerAdmin();
    partialBreadcrumb();
    $entidad = getEntidadDinamica();
?>
<div class="contenedor-header index">
    <h1>Lista de Usuarios</h1>
    <p>Esta es la página de usuarios.</p>
</div>
<div class="contenedor-botones">
    <div class="botones-export">
        <form class="formExportarPdf" id="formExportarPdf" action="<?= BASE_URL ?>usuario/exportarPdf" method="POST" target="_blank">
            <input type="hidden" name="ids" id="idsSeleccionadosPdf">
            <button type="submit" class="btn-export btn-pdf">
                <i class="ri-file-pdf-2-line"></i><span class="export-text">PDF</span>
            </button>
        </form>
        <form class="formExportarExcel" id="formExportarExcel" action="<?= BASE_URL ?>usuario/exportarExcel" method="POST">
            <input type="hidden" name="ids" id="idsSeleccionadosExcel">
            <button type="submit" class="btn-export btn-excel">
                <i class="ri-file-excel-2-line"></i><span class="export-text">Excel</span>
            </button>
        </form>
        <button class="btn-export btn-danger eliminar-seleccion" id="btnEliminarSeleccionados">
            <i class="ri-delete-bin-7-line"></i><span class="export-text">Eliminar</span>
        </button>
        <button class="btn-export btn-secondary cancelar-seleccion" id="btnCancelarSeleccion">
            <i class="ri-close-circle-line"></i><span class="export-text">Cancelar</span>
        </button>
    </div>
</div>
<div class="control-panel">
    <div class="buscador-container">
        <i class="ri-search-eye-line buscador-icon"></i>
        <input type="text" id="buscadorPersonalizado" placeholder="Buscar usuario por nombre o correo..." class="control-buscador" name="buscador">
    </div>
    <div class="selector">
        <span>Filas por página</span>
        <div class="selector-input">
            <i class="ri-arrow-down-s-line selector-icon"></i>
            <select id="selectorCantidad" class="control-selector">
                <option value="5">5</option>
                <option value="10" selected>10</option>
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
        <span>Filtrar Rol</span>
        <div class="selector-input">
            <i class="ri-shield-user-line selector-icon"></i>
            <select id="filtroRol" class="control-selector">
                <option value="">Todos</option>
                <?php foreach ($roles as $rol): ?>
                    <option value="<?= htmlspecialchars($rol->getNombre()) ?>">
                        <?= htmlspecialchars($rol->getNombre()) ?>
                    </option>
                <?php endforeach; ?>
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
                <th class="column-img-th">Foto</th>
                <th class="column-name-th">Nombre</th>
                <th class="column-apellido-th">Apellido</th>
                <th class="column-correo-th">Correo</th>
                <th class="column-rol-th">Rol</th>
                <th class="column-telefono-th">Teléfono</th>
                <th class="column-estado-th">Estado</th>
                <th class="column-fecha-th">Fecha</th>
                <th class="column-opciones-sm-th">Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td class="column-check-td"><input type="checkbox" class="check-row" value="<?= $usuario->getId() ?>"></td>
                        <td class="column-id-td"><?= htmlspecialchars($usuario->getId()) ?></td>
                        <td class="column-img-td"> 
                            <?php if ($usuario->getAvatar()): ?>
                                <img src="<?= htmlspecialchars($usuario->getAvatar()) ?>" 
                                    alt="Usuario" class="avatar-img">
                            <?php else: ?>
                                <div class="avatar-iniciales" style="background: <?= $usuario->getColorAvatar() ?>;">
                                    <?= $usuario->getIniciales() ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="column-name-td">
                            <div>
                                <?= htmlspecialchars($usuario->getNombre()) ?><strong class="column-mbdata-td"> - #<?= htmlspecialchars($usuario->getId()) ?></strong>
                            </div>
                        </td>
                        <td class="column-apellido-td"><?= htmlspecialchars($usuario->getApellido()) ?></td>
                        <td class="column-correo-td"><?= htmlspecialchars($usuario->getCorreo()) ?></td>
                        <td class="column-rol-td"><?= htmlspecialchars($usuario->getNombreRol()) ?></td>
                        <td class="column-telefono-td"><?= htmlspecialchars($usuario->getTelefono() ?? 'Sin teléfono') ?></td>
                        <td class="column-estado-td">
                            <span class="estado-texto d-print-inline"><?= $usuario->getEstado() ? 'Visible' : 'Oculto' ?></span>
                            <label class="switch-tabla">
                                <input type="checkbox" class="toggle-estado" 
                                    data-id="<?= $usuario->getId() ?>" 
                                    <?= $usuario->getEstado() ? 'checked' : '' ?>>
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td class="column-fecha-td"><?= htmlspecialchars($usuario->getCreatedAt()) ?></td>
                        <td class="column-opciones-sm-td">
                            <div class="table-botones">
                                <button class="btn-ver-usuario boton btn-info" data-id="<?= $usuario->getId() ?>" title="Ver detalles">
                                    <span class="boton-icon"><i class="ri-eye-2-fill"></i></span>
                                    <span class="boton-text">Ver</span>
                                </button>
                                <a href="<?= BASE_URL ?>usuario/edit/<?= $usuario->getId() ?>" class="boton btn-warning" title="Editar usuario">
                                    <span class="boton-icon"><i class="ri-edit-circle-fill"></i></span>
                                    <span class="boton-text">Editar</span>
                                </a>
                                <button type="button" class="boton btn-danger" 
                                    data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                    data-id="<?= $usuario->getId() ?>" title="Eliminar usuario">
                                    <span class="boton-icon"><i class="ri-delete-bin-2-fill"></i></span>
                                    <span class="boton-text">Borrar</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Detalles Usuario -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg"><!-- modal-lg para más espacio -->
    <div class="modal-content">
      <div class="modal-header border-0">
        <h6 class="modal-title">Detalles del Usuario</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <h2 id="modal-tabla-titulo" class="modal-nombre"></h2>

        <div class="row align-items-center w-100">
          <!-- Columna izquierda: Foto + Botón -->
          <div class="col-12 col-md-4 d-flex justify-content-center align-items-center flex-column text-md-start mb-3 mb-md-0">
            <img id="modal-foto" class="img-thumbnail d-none modal-foto mb-3" alt="Foto Usuario">
            <div id="modal-sin-foto" class="d-none mb-3">
              <i class="ri-account-circle-line fs-1"></i><br>Sin foto
            </div>
            <div>
              <a id="modal-edit-link" class="boton btn-warning">
                <span class="boton-text">Editar</span>
                <span class="boton-icon"><i class="ri-edit-circle-fill"></i></span>
              </a>
            </div>
          </div>

          <!-- Columna derecha: Tabla -->
          <div class="col-12 col-md-8">
            <table class="table-sm w-100">
              <tr><td class="fw-bolder px-2">ID:</td><td class="text-start px-2" id="modal-id"></td></tr>
              <tr><td class="fw-bolder px-2">Nombre:</td><td class="text-start px-2" id="modal-nombre"></td></tr>
              <tr><td class="fw-bolder px-2">Apellido:</td><td class="text-start px-2" id="modal-apellido"></td></tr>
              <tr><td class="fw-bolder px-2">Correo:</td><td class="text-start px-2" id="modal-correo"></td></tr>
              <tr><td class="fw-bolder px-2">Dirección:</td><td class="text-start px-2" id="modal-direccion"></td></tr>
              <tr><td class="fw-bolder px-2">DNI:</td><td class="text-start px-2" id="modal-dni"></td></tr>
              <tr><td class="fw-bolder px-2">Rol:</td><td class="text-start px-2" id="modal-rol"></td></tr>
              <tr><td class="fw-bolder px-2">Teléfono:</td><td class="text-start px-2" id="modal-telefono"></td></tr>
              <tr><td class="fw-bolder px-2">Estado:</td><td class="text-start px-2" id="modal-estado"></td></tr>
              <tr><td class="fw-bolder px-2">Último login:</td><td class="text-start px-2" id="modal-ultimo-login"></td></tr>
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
        </div>
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
    document.querySelectorAll('.btn-ver-usuario').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`${'<?= BASE_URL ?>'}usuario/show/${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                const titulo = `${data.nombre ?? ''} ${data.apellido ?? ''}`.trim();
                document.getElementById('modal-tabla-titulo').textContent = titulo || '-';
                document.getElementById('modal-id').textContent = data.id ?? '-';
                document.getElementById('modal-nombre').textContent = data.nombre ?? '-';
                document.getElementById('modal-apellido').textContent = data.apellido ?? '-';
                document.getElementById('modal-correo').textContent = data.correo ?? '-';
                document.getElementById('modal-direccion').innerHTML = data.direccion ?? '<span class="text-muted">Sin dirección</span>';
                document.getElementById('modal-dni').innerHTML = data.dni ?? '<span class="text-muted">Sin DNI</span>';
                document.getElementById('modal-rol').textContent = data.rol ?? '-';
                document.getElementById('modal-telefono').innerHTML = data.telefono ?? '<span class="text-muted">Sin teléfono</span>';
                document.getElementById('modal-estado').innerHTML = data.estado 
                    ? '<span class="medalla bg-success"><i class="ri-eye-line"></i> Habilitado</span>'
                    : '<span class="medalla bg-secondary"><i class="ri-eye-off-line"></i> Deshabilitado</span>';
                document.getElementById('modal-ultimo-login').textContent = data.ultimo_login ?? '-';
                document.getElementById('modal-creado-por').textContent = data.creado_por ?? '-';
                document.getElementById('modal-modificado-por').textContent = data.modificado_por ?? '-';
                document.getElementById('modal-created-at').textContent = data.created_at || '-';
                document.getElementById('modal-updated-at').textContent = data.updated_at || '-';

                const foto = document.getElementById('modal-foto');
                const sinFoto = document.getElementById('modal-sin-foto');
                if (data.imagen_url) {
                    foto.src = data.imagen_url;
                    foto.classList.remove('d-none');
                    sinFoto.classList.add('d-none');
                } else {
                    foto.classList.add('d-none');
                    sinFoto.classList.remove('d-none');
                }

                new bootstrap.Modal(document.getElementById('viewModal')).show();
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
