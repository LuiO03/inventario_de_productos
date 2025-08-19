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
    });
</script>