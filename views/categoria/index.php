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
                                    <?= $categoria->getEstado() ? 'checked' : '' ?>>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-ver-categoria').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;

                fetch(`${'<?= BASE_URL ?>'}categoria/show/${id}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Error al obtener datos');
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('modal-id').textContent = data.id || '-';
                        document.getElementById('modal-nombre').textContent = data.nombre || '-';
                        document.getElementById('modal-descripcion').textContent = data.descripcion || '(Sin descripción)';
                        document.getElementById('modal-estado').innerHTML = data.estado ?
                            '<span class="medalla bg-success"><i class="ri-eye-line"></i> Habilitado</span>' :
                            '<span class="medalla bg-secondary"><i class="ri-eye-off-line"></i> Deshabilitado</span>';
                        document.getElementById('modal-creado-por').textContent = data.creado_por ?? '-';
                        document.getElementById('modal-modificado-por').textContent = data.modificado_por ?? '-';
                        document.getElementById('modal-created-at').textContent = data.created_at || '-';
                        document.getElementById('modal-updated-at').textContent = data.updated_at || '-';

                        const contenedorSubcategorias = document.getElementById('modal-subcategorias');
                        contenedorSubcategorias.innerHTML = ''; // Limpiar antes

                        if (data.subcategorias && data.subcategorias.length > 0) {
                            data.subcategorias.forEach(sub => {
                                const li = document.createElement('li');
                                li.classList.add(sub.estado ? 'text-primario' : 'text-muted'); // Aplica clase global
                                li.classList.add('text-md');

                                const icono = sub.estado ? 'ri-eye-fill' : 'ri-eye-close-fill';

                                li.innerHTML = `
                                    <i class="${icono} me-1"></i> <span>#${sub.id} -</span>
                                    ${sub.nombre}
                                `;
                                contenedorSubcategorias.appendChild(li);
                            });
                        } else {
                            contenedorSubcategorias.innerHTML = `
                                <li class="medalla bg-secondary text-white">
                                    <i class="ri-price-tag-line"></i>Sin subcategorías
                                </li>
                            `;
                        }

                        const padre = document.getElementById('modal-categoria-padre');
                        if (data.categoria_padre) {
                            const claseEstado = data.categoria_padre.estado ? 'text-primario' : 'text-muted';
                            padre.classList.add(claseEstado);
                            const icono = data.categoria_padre.estado ? 'ri-eye-fill' : 'ri-eye-close-fill';
                            padre.innerHTML = `
                            <i class="${icono} me-1 ${claseEstado}"></i> 
                            <span>#${data.categoria_padre.id} -</span> 
                            ${data.categoria_padre.nombre} `;
                        } else {
                            padre.innerHTML = `
                            <li class="medalla bg-secondary text-white">
                                <i class="ri-price-tag-2-line"></i>Sin categoría padre
                            </li>`;
                        }
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
                        /* esto es para # mostrar el modal */
                        const viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
                        viewModal.show();
                    })
                    .catch(error => {
                        console.error(error);
                        document.getElementById('modal-id').textContent = '-';
                        document.getElementById('modal-nombre').textContent = 'Error';
                        document.getElementById('modal-descripcion').textContent = '-';
                        document.getElementById('modal-estado').innerHTML = '<span class="medalla bg-danger">Error</span>';
                    });
            });
        });
        document.querySelectorAll('.toggle-estado').forEach(input => {
            input.addEventListener('change', function() {
                const id = this.dataset.id;
                const nuevoEstado = this.checked ? 1 : 0;

                fetch(`<?= BASE_URL ?>categoria/toggleEstado/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            estado: nuevoEstado
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            alert('Error al actualizar el estado');
                            this.checked = !nuevoEstado; // revertir si falla
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert('Error al actualizar el estado');
                        this.checked = !nuevoEstado;
                    });
            });
        });
    });
</script>
<!-- Modal ver registro -->
<div class="modal fade modal-slide-animate" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-bold" id="viewModalLabel">Detalles de la Categoría</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <h2 class="modal-nombre" id="modal-nombre"></h2>
                <table class="table-sm w-100">
                    <tbody>
                        <tr>
                            <td class="text-start fw-bolder px-2">ID:</td>
                            <td class="text-start px-2"><span id="modal-id"></span></td>
                        </tr>
                        <tr>
                            <td class="text-start fw-bolder px-2">Descripción:</td>
                            <td class="text-start px-2"><span id="modal-descripcion"></span></td>
                        </tr>
                        <tr>
                            <td class="text-start fw-bolder px-2">Estado:</td>
                            <td class="text-start px-2" class=""><span id="modal-estado"></span></td>
                        </tr>
                        <tr>
                            <td class="text-start fw-bolder px-2">Categoría Padre:</td>
                            <td class="text-start px-2" id="modal-categoria-padre">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start fw-bolder px-2">Subcategorías:</td>
                            <td class="text-start px-2">
                                <ul id="modal-subcategorias" class="mb-0 px-0 small"></ul>
                            </td>
                        </tr>
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
                    </tbody>
                </table>
                <div class="text-center">
                    <img id="modal-imagen" class="img-thumbnail d-none modal-imagen" alt="Imagen Marca">
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