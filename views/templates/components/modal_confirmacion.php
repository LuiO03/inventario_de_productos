<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header bg-danger border-0">
        <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.
      </div>
      <div class="modal-footer border-0">
        <form id="deleteForm" method="POST" action="">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Cancelar</button>
          <button type="submit" class="btn btn-danger">Si, Eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const form = document.getElementById('deleteForm');
        form.action = "<?= BASE_URL ?>producto/delete/" + id;
    });
</script>