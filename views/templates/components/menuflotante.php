<?php
    $datos = getBreadcrumbData();
?>
<div class="fab-wrapper d-block d-sm-none" id="fabWrapper">
  <!-- Fondo externo independiente -->
  <div class="fab-overlay" id="fabOverlay"></div>

  <!-- Acciones ocultas -->
  <div class="fab-actions">
    <button class="fab-btn pdf" title="Exportar PDF"><i class="ri-file-pdf-2-fill"></i></button>
    <button class="fab-btn excel" title="Exportar Excel"><i class="ri-file-excel-fill"></i></button>
    <a href="<?= BASE_URL .$datos['titulo']?>/create" class="fab-btn agregar" title="Agregar <?= $datos['titulo'] ?>">
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