<?php 
    headerAdmin();
    partialBreadcrumb();
?>
<div class="contenedor-header">
    <h1>Lista de Marcas</h1>
    <p>Esta es la página de marcas.</p>
    <div class="contenedor-botones">
        <div class="button-borders"><button class="btn-export btn-copy"><i class="ri-file-copy-line"></i> Copiar</button></div>
        <div class="button-borders"><button class="btn-export btn-excel"><i class="ri-file-excel-2-line"></i> Excel</button></div>
        <div class="button-borders">
            <a href="<?= BASE_URL ?>marca/exportarPDF" class="btn-export btn-pdf" target="_blank">
                <i class="ri-file-pdf-2-line"></i> PDF
            </a>
        </div>
        <div class="button-borders"><button class="btn-export btn-print"><i class="ri-printer-line"></i> Imprimir</button></div>
    </div>
</div>

<div class="contenedor">
    <div class="control-panel">
        <div class="buscador-container">
            <i class="ri-search-eye-line buscador-icon"></i>
            <input type="text" id="buscadorPersonalizado" placeholder="Buscar marca por nombre o descripción..." class="control-buscador">
        </div>
        <div class="selector-container">
            <i class="ri-arrow-down-s-line selector-icon"></i>
            <span>Filas por página</span>
            
            <select id="selectorCantidad" class="control-selector">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>
    <table id="tabla" class="table-sm w-100 tabla-responsive">
        <thead>
            <tr>
                <th class="column-id-th">ID</th>
                <th>Img</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Descripción</th>
                <th class="column-estado-th">Estado</th>
                <th class="column-opciones-th">Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($marcas)): ?>
                <?php foreach ($marcas as $marca): ?>
                    <tr>
                        <td class="column-id-td" data-label="ID:"><?= htmlspecialchars($marca->getId()) ?></td>
                        <td data-label="Imagen:" class="text-center">
                            <?php if (!empty($marca->imagen_url)): ?>
                                <img src="<?= BASE_URL ?>public/images/marcas/<?= htmlspecialchars($marca->getImagen()) ?>" alt="Marca" style="width: 45px; height: 45px; object-fit: cover; border-radius: 50%; pointer-events: none;">
                            <?php else: ?>
                                <span class="text-primario fw-bolder">Sin imagen</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-start" data-label="Nombre:"><?= htmlspecialchars($marca->getNombre()) ?></td>
                        <td class="text-start" data-label="Descripción:"><?= htmlspecialchars($marca->getDescripcion() ?: '[Sin descripción]') ?></td>
                        <td class="column-estado-td" data-label="Estado:">
                            <label class="switch-tabla">
                                <input type="checkbox" class="toggle-estado" data-id="<?= $marca->getId() ?>" <?= $marca->getEstado() ? 'checked' : '' ?>>
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td class="column-opciones-td">
                            <div class="table-botones">
                                <button class="btn-ver-marca btn btn-sm btn-info" data-id="<?= $marca->getId() ?>">
                                    <span class="boton-text">Ver</span>
                                    <span class="boton-icon"><i class="ri-eye-2-fill"></i></span>
                                </button>
                                <a href="<?= BASE_URL ?>marca/edit/<?= urlencode($marca->getSlug()) ?>" class="btn btn-sm btn-warning">
                                    <span class="boton-icon"><i class="ri-edit-circle-fill"></i></span>
                                    <span class="boton-text">Editar</span>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $marca->getId() ?>">
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
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-ver-marca').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`${'<?= BASE_URL ?>'}marca/show/${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('modal-id').textContent = data.id ?? '-';
                document.getElementById('modal-nombre').textContent = data.nombre ?? '-';
                document.getElementById('modal-descripcion').textContent = data.descripcion ?? '(Sin descripción)';
                document.getElementById('modal-estado').innerHTML = data.estado 
                    ? '<span class="medalla bg-success"><i class="ri-eye-line"></i> Habilitado</span>'
                    : '<span class="medalla bg-secondary"><i class="ri-eye-off-line"></i> Deshabilitado</span>';
                document.getElementById('modal-creado-por').textContent = data.creado_por ?? '-';
                document.getElementById('modal-created-at').textContent = data.created_at ?? '-';
                document.getElementById('modal-modificado-por').textContent = data.modificado_por ?? '-';
                document.getElementById('modal-updated-at').textContent = data.updated_at ?? '-';
                const img = document.getElementById('modal-imagen');
                const sinImagen = document.getElementById('modal-sin-imagen');

                if (data.imagen_url) {
                    img.src = data.imagen_url;
                    img.classList.remove('d-none');
                    sinImagen.classList.add('d-none');
                } else {
                    img.classList.add('d-none');
                    sinImagen.classList.remove('d-none');
                }
                const viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
                viewModal.show();
            });
        });
    });

    document.querySelectorAll('.toggle-estado').forEach(input => {
        input.addEventListener('change', function() {
            const id = this.dataset.id;
            const estado = this.checked ? 1 : 0;
            fetch(`<?= BASE_URL ?>marca/toggleEstado/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ estado })
            })
            .then(res => res.json())
            .then(data => { if (!data.success) alert('Error al actualizar el estado'); })
            .catch(() => alert('Error de red'));
        });
    });
});
</script>

<!-- Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h6 class="modal-title">Detalles de la Marca</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h2 class="modal-nombre" id="modal-nombre"></h2>
                <table class="table-sm w-100">
                    <tr><td class="text-start fw-bolder">ID:</td><td class="text-start px-2" id="modal-id"></td></tr>
                    <tr><td class="text-start fw-bolder">Descripción:</td><td class="text-start px-2" id="modal-descripcion"></td></tr>
                    <tr><td class="text-start fw-bolder">Estado:</td><td class="text-start px-2" id="modal-estado"></td></tr>
                    <tr>
                        <td class="text-start fw-bolder">Creado por:</td>
                        <td class="text-start px-2">
                            <span id="modal-creado-por"></span>
                            <div>
                                <i class="ri-calendar-schedule-fill text-primario"></i>
                                <span class="text-primario" id="modal-created-at"></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start fw-bolder">Modificado por:</td>
                        <td class="text-start px-2">
                            <span id="modal-modificado-por"></span>
                            <div>
                                <i class="ri-calendar-schedule-fill text-primario"></i>
                                <span class="text-primario" id="modal-updated-at"></span>
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="text-center">
                    <img id="modal-imagen" class="img-thumbnail d-none modal-imagen-sm" alt="Imagen Marca">
                    <div id="modal-sin-imagen" class="modal-sin-imagen d-none">
                        <i class="ri-landscape-fill"></i>
                        <span>Sin imagen</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center mt-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>


<?php
menuFlotante();
modalFlash($mensaje);
modalConfirmacion();
footerAdmin();
?>
