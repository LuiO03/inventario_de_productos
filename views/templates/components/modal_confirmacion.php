<?php
  $entidad = getEntidadDinamica();
?>

<!-- Modal de Confirmación Dinámica -->
<div class="modal fade modal-slide-animate" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Encabezado -->
      <div class="modal-header bg-danger border-0">
        <h6 class="modal-title fw-bold text-white" id="confirmModalLabel">Confirmar Acción</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!-- Cuerpo -->
      <di class="modal-body py-3 px-4">
        <div class="row w-100">
          <!-- Texto (Columna izquierda) -->
          <div class="col-9 d-flex flex-column justify-content-between">
            <!-- Texto -->
            <div class="mb-3">
              <h6 id="confirmModalTitulo" class="fw-bold mb-1">¿Estás seguro?</h6>
              <p id="confirmModalTexto" class="mb-0">Esta acción no se puede deshacer.</p>
            </div>
          </div>
          <!-- Ícono (Columna derecha) -->
          <div class="col-3 d-flex align-items-start justify-content-center">
            <i class="ri-question-fill text-danger py-0" style="font-size: 4rem;"></i>
          </div>
        </div>
        <!-- Botones -->
        <form id="confirmModalForm" method="POST" action="" class="d-flex gap-2 justify-content-end align-items-center mt-auto w-100">
          <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF(); ?>">
          <div id="confirmModalCamposExtra"></div>
          <button type="button" class="boton bg-modal-close" data-bs-dismiss="modal">
              <span class="boton-icon text-base-inverted"><i class="ri-close-line"></i></span>
              <span class="boton-text">No, Cancelar</span>
          </button>
          <button type="submit" class="boton bg-danger" data-bs-dismiss="modal">
              <span class="boton-icon"><i class="ri-delete-bin-2-line"></i></span>
              <span class="boton-text">Sí, confirmar</span>
          </button>
        </form>
    </div>

  </div>
</div>
</div>



<script>
  document.querySelectorAll('[data-bs-target="#deleteModal"]').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.dataset.id;
      mostrarModalConfirmacion({
        titulo: `¿Eliminar esta ${'<?= $entidad['titulo'] ?>'}?`,
        mensaje: 'Esta acción no se puede deshacer.',
        action: `<?= BASE_URL ?>${'<?= $entidad['titulo'] ?>'}/delete/${id}`
      });
    });
  });

  // ELIMINAR SELECCIONADOS
  document.getElementById('btnEliminarSeleccionados').addEventListener('click', function() {
    const ids = Array.from(document.querySelectorAll('.check-row:checked')).map(checkbox => checkbox.value);
    if (ids.length === 0) {
      alert('No hay elementos seleccionados');
      return;
    }
    mostrarModalConfirmacion({
      titulo: `¿Deseas eliminar esto(s) ${ids.length} registro(s)?`,
      mensaje: 'Se eliminarán permanentemente.',
      action: `<?= BASE_URL ?>${'<?= $entidad['titulo'] ?>'}/deletemultiple`,
      camposExtra: {
        ids: ids.join(',')
      }
    });
  });

  function mostrarModalConfirmacion({
      titulo = '¿Estás seguro?',
      mensaje = 'Esta acción no se puede deshacer.',
      action = '',
      method = 'POST',
      camposExtra = {}
    }) {
    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const form = document.getElementById('confirmModalForm');
    const tituloEl = document.getElementById('confirmModalTitulo');
    const textoEl = document.getElementById('confirmModalTexto');
    const camposExtraEl = document.getElementById('confirmModalCamposExtra');

    tituloEl.textContent = titulo;
    textoEl.textContent = mensaje;
    form.action = action;
    form.method = method;

    // Limpiar campos extra
    camposExtraEl.innerHTML = '';

    // Agregar inputs ocultos dinámicos si se requieren
    for (const name in camposExtra) {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = name;
      input.value = camposExtra[name];
      camposExtraEl.appendChild(input);
    }

    modal.show();
  }


  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-estado').forEach(input => {
      input.addEventListener('change', function() {

        const id = this.dataset.id;
        const nuevoEstado = this.checked ? 1 : 0;

        fetch(`<?= BASE_URL ?>${'<?= $entidad['titulo'] ?>'}/toggleEstado/${id}`, {
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
              this.checked = !nuevoEstado;
            } else {
              const fila = $(this).closest('tr');
              const nuevaEtiqueta = nuevoEstado ? 'Visible' : 'Oculto';

              // Actualiza el DOM visible
              const spanTexto = fila.find('.estado-texto');
              if (spanTexto.length) {
                spanTexto.text(nuevaEtiqueta);
              }

              // 🔁 Ahora actualiza también la celda en el modelo de DataTables
              const indiceColumna = tabla.column('.column-estado-th').index(); // usa clase para encontrar la columna de estado
              tabla.cell(fila, indiceColumna).data(`
                            <span class="estado-texto d-print-inline">${nuevaEtiqueta}</span>
                            <label class="switch-tabla">
                                <input type="checkbox" class="toggle-estado" data-id="${id}" ${nuevoEstado ? 'checked' : ''} name="estado">
                                <span class="slider"></span>
                            </label>
                        `).draw(false);

              // Reasignar el listener al nuevo switch renderizado por DataTables
              fila.find('.toggle-estado').on('change', arguments.callee);
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