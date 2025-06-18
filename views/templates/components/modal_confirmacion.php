<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade modal-slide-animate" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header bg-danger border-0">
        <h6 class="modal-title fw-bold" id="deleteModalLabel">Confirmar Eliminación</h6>
        <button type="button" class="btn-close btn-close-white bg-white rounded-circle p-2" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body text-center">
        <i class="ri-close-circle-line text-danger mb-3" style="font-size: 6rem;"></i>
        <h3 class="fw-bold">
          ¿Estás seguro de que deseas eliminar este producto?<br>
        </h3>
        <p>Esta acción no se puede deshacer.</p>
      </div>
      
      <div class="modal-footer border-0 justify-content-center">
        <form id="deleteForm" method="POST" action="" class="d-flex gap-2">
          
          <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF(); ?>">

          <button type="button" class="btn btn-sm btn-light d-flex align-items-center gap-1" data-bs-dismiss="modal">
            <i class="ri-close-line"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center gap-1">
            <i class="ri-delete-bin-line"></i> Eliminar
          </button>
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