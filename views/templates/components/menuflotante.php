<?php
  $entidad = getEntidadDinamica();
?>
<div class="fab-wrapper" id="fabWrapper">
  <!-- Fondo externo independiente -->
  <div class="fab-overlay" id="fabOverlay"></div>

  <!-- Acciones ocultas -->
  <div class="fab-actions">
    <form id="formExportarPdf" class="formExportar" action="<?= BASE_URL ?><?= $entidad['titulo'] ?>/exportarPdf" method="POST" target="_blank">
      <input type="hidden" name="ids" id="idsSeleccionadosPdf">
      <button class="fab-btn pdf" title="Exportar PDF"><i class="ri-file-pdf-2-fill"></i></button>
    </form>
    <form id="formExportarExcel" class="formExportar" action="<?= BASE_URL ?><?= $entidad['titulo'] ?>/exportarExcel" method="POST">
      <input type="hidden" name="ids" id="idsSeleccionadosExcel">
      <button class="fab-btn excel" title="Exportar Excel"><i class="ri-file-excel-fill"></i></button>
    </form>
    <a href="<?= BASE_URL .$entidad['titulo']?>/create" class="fab-btn agregar" title="Agregar <?= $entidad['titulo'] ?>">
      <i class="ri-add-circle-fill"></i>
    </a>
  </div>

  <!-- Botón principal -->
  <button class="fab-btn toggle" id="fabToggle"><i class="ri-add-large-fill"></i></button>
</div>

<script>
  const fabWrapper = document.getElementById('fabWrapper');
  const fabToggle = document.getElementById('fabToggle');
  const fabOverlay = document.getElementById('fabOverlay');

  fabToggle.addEventListener('click', (e) => {
    e.stopPropagation();
    fabWrapper.classList.toggle('open');
    fabOverlay.classList.toggle('open');
  });

  document.addEventListener('click', (e) => {
    if (!fabWrapper.contains(e.target)) {
      fabWrapper.classList.remove('open');
      fabOverlay.classList.remove('open');
    }
  });
</script>