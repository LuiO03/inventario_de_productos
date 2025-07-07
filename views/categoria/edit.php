<?php
headerAdmin();
partialBreadcrumb();
?>

<div class="contenedor-header">
  <h1 class="text-center">
    Editar Categoría - #<?= htmlspecialchars($categoria->getId()) ?>
  </h1>
  <p class="text-center">
    Aquí puedes editar la información de la categoría seleccionada.
  </p>
</div>

<form action="<?= BASE_URL ?>categoria/update/<?= $categoria->getId() ?>" method="post" enctype="multipart/form-data" class="formulario" autocomplete="off">
  <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF(); ?>">
  <input type="hidden" name="id" value="<?= htmlspecialchars($categoria->getId()) ?>">

  <div class="formulario-contenido">
    <!-- Columna de inputs -->
    <div class="formulario-columna-nowrap">
      <?php alertValidate(); ?>
      <!-- Nombre -->
      <div class="input-group-nowrap">
        <label for="nombre" class="form-label">Nombre de la Categoría</label>
        <div class="input-icono">
          <i class="ri-price-tag-3-line"></i>
          <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($categoria->getNombre()) ?>" required placeholder="Ingrese el nombre">
        </div>
      </div>

      <!-- Estado -->
      <div class="input-group-nowrap">
        <label for="estado" class="form-label">Estado</label>
        <div class="input-icono">
          <i class="ri-focus-2-line"></i>
          <select id="estado" name="estado" class="form-select">
            <option value="1" <?= $categoria->getEstado() ? 'selected' : '' ?>>Habilitado</option>
            <option value="0" <?= !$categoria->getEstado() ? 'selected' : '' ?>>Deshabilitado</option>
          </select>
        </div>
      </div>

      <!-- Descripción -->
      <div class="input-group-nowrap">
        <label for="descripcion" class="form-label">Descripción</label>
        <div class="input-icono">
          <i class="ri-file-text-line"></i>
          <textarea id="descripcion" name="descripcion" rows="3" placeholder="Describe brevemente..."><?= htmlspecialchars($categoria->getDescripcion()) ?></textarea>
        </div>
      </div>
    </div>
    <div class="formulario-columna-nowrap">
      <!-- Subir nueva imagen o mostrar actual -->
      <div class="input-group-nowrap">

        <label class="form-label">Imagen de la categoría</label>

        <div class="contenedor-upload">
          <div class="upload-header" id="preview-container">
            <?php if ($categoria->getImagen()) : ?>
              <img id="preview-imagen"
                src="<?= BASE_URL . 'public/images/categorias/' . $categoria->getImagen() ?>"
                alt="Vista previa de la imagen" draggable="false" style="display: block;">
              <i class="ri-image-add-line upload-icon" id="upload-icon" style="display: none;"></i>
              <p id="preview-texto" style="<?= $categoria->getImagen() ? 'display: none;' : 'display: block;' ?>">
                ¡Arrastre una imagen aquí! <br>
                o seleccionelo desde su dispositivo.
              </p>
            <?php else : ?>
              <img id="preview-imagen" src="" alt="Vista previa vacía" draggable="false" style="display: none;">
              <i class="ri-file-close-line upload-icon" id="upload-icon" style="display: block;"></i>
              <p id="preview-texto" style="display: block;">Imagen no disponible o eliminada</p>
            <?php endif; ?>
          </div>

          <!-- Footer con archivo, botón quitar y botón subir imagen -->
          <div class="upload-footer">
            <!-- Nombre del archivo -->
            <label for="imagen" class="info-imagen">

              <i class="ri-upload-2-line"></i>
              <p id="nombre-archivo" title="<?= $categoria->getImagen() ?>"><?= $categoria->getImagen() ?: 'Ningún archivo seleccionado' ?></p>
              <i class="ri-folder-open-line"></i>
            </label>

            <!-- Botón: quitar imagen -->
            <?php if ($categoria->getImagen()) : ?>
              <button type="button" id="btn-quitar-imagen" class="boton-quitar-imagen" title="Quitar imagen actual">
                <i class="ri-delete-bin-line"></i>
                <span>Quitar imagen</span>
              </button>
            <?php endif; ?>
            <!-- Input oculto + file -->
            <input type="hidden" name="quitar_imagen" id="quitar-imagen" value="0">
            <input type="file" name="imagen" id="imagen" accept="image/*">
          </div>

        </div>
      </div>
    </div>
  </div>
  <!-- Botones -->
  <div class="formulario-acciones">
    <a href="<?= BASE_URL ?>categoria/index" class="boton-form boton-volver">
      <i class="ri-arrow-left-circle-fill"></i> Volver
    </a>
    <button type="submit" class="boton-form boton-actualizar">
      <i class="ri-loop-left-line"></i> Actualizar Categoría
    </button>
  </div>
</form>
<script>
  const inputImagen = document.getElementById('imagen');
  const imgPreview = document.getElementById('preview-imagen');
  const icono = document.getElementById('upload-icon');
  const texto = document.getElementById('preview-texto');
  const nombreArchivo = document.getElementById('nombre-archivo');
  const inputQuitar = document.getElementById('quitar-imagen');
  const dropArea = document.getElementById('preview-container');
  const btnQuitar = document.getElementById('btn-quitar-imagen');

  // Vista previa al seleccionar imagen
  inputImagen?.addEventListener('change', () => {
    const archivo = inputImagen.files[0];
    if (archivo) {
      const reader = new FileReader();
      reader.onload = e => {
        imgPreview.src = e.target.result;
        imgPreview.style.display = 'block';
        icono.style.display = 'none';
        texto.style.display = 'none';
        nombreArchivo.textContent = archivo.name;
        nombreArchivo.title = archivo.name;
        inputQuitar.value = '0';
      };
      reader.readAsDataURL(archivo);
    }
  });

  // Botón quitar imagen
  btnQuitar?.addEventListener('click', () => {
    imgPreview.src = '';
    imgPreview.style.display = 'none';
    icono.style.display = 'block';
    texto.style.display = 'block';
    nombreArchivo.textContent = 'Ningún archivo seleccionado';
    inputImagen.value = '';
    inputQuitar.value = '1';
  });

  // Eventos Drag & Drop
  ['dragenter', 'dragover'].forEach(evt =>
    dropArea.addEventListener(evt, e => {
      e.preventDefault();
      dropArea.classList.add('arrastrando');
    })
  );

  ['dragleave', 'drop'].forEach(evt =>
    dropArea.addEventListener(evt, e => {
      e.preventDefault();
      dropArea.classList.remove('arrastrando');
    })
  );

  dropArea.addEventListener('drop', e => {
    const archivos = e.dataTransfer.files;
    if (archivos.length > 0) {
      inputImagen.files = archivos;
      inputImagen.dispatchEvent(new Event('change'));
    }
  });
</script>

<?php
modalFlash($mensaje);
footerAdmin();
?>