<!-- Modal ver registro -->
<div class="modal fade modal-slide-animate" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-bold" id="viewModalLabel">Detalles de la Categoría</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <h2 id="modal-tabla-titulo" class="modal-nombre"></h2>
                <div class="modal-buttons-container">
                    <a id="modal-edit-link" class="boton btn-warning btn-sm">
                        <span class="boton-text">Editar</span>
                        <span class="boton-icon"><i class="ri-edit-circle-fill"></i></span>
                    </a>
                </div>
                <table class="table-sm w-100">
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
                        const titulo = `${data.nombre}`;
                        document.getElementById('modal-tabla-titulo').textContent = titulo || '-';
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
                                const a = document.createElement('a');
                                const claseEstado = sub.estado ? 'text-link' : 'text-muted';
                                const icono = sub.estado ? 'ri-eye-fill' : 'ri-eye-close-fill';

                                a.classList.add('text-md', claseEstado);
                                a.href = `${'<?= BASE_URL ?>'}categoria/edit/${sub.id}`;

                                a.innerHTML = `
                                    <li>
                                        <i class="${icono} me-1 ${claseEstado}"></i>
                                        <span>#${sub.id} -</span> ${sub.nombre}
                                    </li>
                                `;
                                contenedorSubcategorias.appendChild(a);
                            });
                        } else {
                            contenedorSubcategorias.innerHTML = `
                                <li class="medalla bg-secondary text-white">
                                    <i class="ri-price-tag-line"></i>Sin subcategorías
                                </li>
                            `;
                        }
                        document.getElementById('modal-edit-link').href = `${'<?= BASE_URL ?>'}categoria/edit/${data.slug}`;
                        const padre = document.getElementById('modal-categoria-padre');
                        padre.innerHTML = ''; // Limpiar antes

                        if (data.categoria_padre) {
                                const claseEstado = data.categoria_padre.estado ? 'text-link' : 'text-muted';
                                const icono = data.categoria_padre.estado ? 'ri-eye-fill' : 'ri-eye-close-fill';

                                const a = document.createElement('a');
                                a.href = `${'<?= BASE_URL ?>'}categoria/edit/${data.categoria_padre.id}`;
                                a.classList.add(claseEstado);

                                a.innerHTML = `
                                    <i class="${icono} me-1 ${claseEstado}"></i>
                                    <span>#${data.categoria_padre.id} -</span> 
                                    ${data.categoria_padre.nombre}
                                `;

                                padre.appendChild(a);
                            } else {
                            padre.innerHTML = `
                                <li class="medalla bg-secondary text-white">
                                    <i class="ri-price-tag-2-line"></i>Sin categoría padre
                                </li>
                            `;
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
        
    });
</script>