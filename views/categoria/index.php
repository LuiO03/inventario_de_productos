<?php
headerAdmin();
partialBreadcrumb();
?>

<!-- Contenido de la información y botones -->
<div class="contenedor-header">
    <h1 class="text-center">Lista de Categorías</h1>
    <p class="text-center">Esta es la página de categorías.</p>
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
    <table id="tablaCategorias" class="table-sm d-none w-100 tabla-responsive">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Descripción</th>
                <th class="text-center">Estado</th>
                <th class="text-center">Opciones</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td data-label="ID:"><?= htmlspecialchars($categoria->getId()) ?></td>
                        <td class="text-start" data-label="Nombre:"><?= htmlspecialchars($categoria->getNombre()) ?></td>
                        <td class="text-start" data-label="Descripción:"><?= htmlspecialchars($categoria->getDescripcion()) ?></td>
                        <td data-label="Estado:">
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

                        <td>
                            <div class="table-botones">
                                <button
                                    class="btn-ver-categoria btn btn-sm btn-info"
                                    data-id="<?= $categoria->getId() ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewModal"
                                    title="Ver Categoría">
                                    <span class="boton-text">Ver</span>
                                    <span class="boton-icon"><i class="ri-eye-2-fill"></i></span>
                                </button>
                                <a href="<?= BASE_URL ?>categoria/edit/<?= $categoria->getId() ?>" title="Editar Categoría" class="btn btn-sm btn-warning">
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
                            '<span class="badge bg-success">Habilitado</span>' :
                            '<span class="badge bg-secondary">Deshabilitado</span>';
                        document.getElementById('modal-creado-por').textContent = data.creado_por ?? '-';
                        document.getElementById('modal-modificado-por').textContent = data.modificado_por ?? '-';
                        document.getElementById('modal-created-at').textContent = data.created_at || '-';
                        document.getElementById('modal-updated-at').textContent = data.updated_at || '-';
                    })
                    .catch(error => {
                        console.error(error);
                        document.getElementById('modal-id').textContent = '-';
                        document.getElementById('modal-nombre').textContent = 'Error';
                        document.getElementById('modal-descripcion').textContent = '-';
                        document.getElementById('modal-estado').innerHTML = '<span class="badge bg-danger">Error</span>';
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
            <div class="modal-header bg-primary border-0">
                <h6 class="modal-title" id="viewModalLabel">Detalles de la Categoría</h6>
                <button type="button" class="btn-close btn-close-white bg-white rounded-circle p-2" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table-sm w-100">
                        <thead>
                            <tr>
                                <th class="text-center">Atributos</th>
                                <th class="text-center">Datos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-start fw-bolder px-2">ID:</td>
                                <td class="text-start px-2"><span id="modal-id"></span></td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bolder px-2">Nombre:</td>
                                <td class="text-start px-2"><span id="modal-nombre"></span></td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bolder px-2">Descripción:</td>
                                <td class="text-start px-2"><span id="modal-descripcion"></span></td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bolder px-2">Estado:</td>
                                <td class="text-start px-2"><span id="modal-estado"></span></td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bolder px-2">Creado por:</td>
                                <td class="text-start px-2">
                                    <span id="modal-creado-por"></span> -
                                    <span class="text-primario fw-bolder" id="modal-created-at"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bolder px-2">Modificado por:</td>
                                <td class="text-start px-2">
                                    <span id="modal-modificado-por"></span> -
                                    <span class="text-primario fw-bolder" id="modal-updated-at"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal"><i class="ri-close-line"></i> Cerrar</button>
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