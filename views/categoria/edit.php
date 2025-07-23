<?php
  headerAdmin();
  partialBreadcrumb();
?>

<div class="contenedor-header">
  <h1>
    Editar Categoría #<?= htmlspecialchars($categoria->getId()) ?>
  </h1>
  <p>
    <?= htmlspecialchars($categoria->getNombre()) ?> 
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
        <div class="input-contenedor">
          <i class="ri-price-tag-3-line" id="input-icono-nombre" ></i>
          <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($categoria->getNombre()) ?>" required placeholder="Ingrese el nombre">
          <i id="input-estado-nombre" class="input-estado"></i>
        </div>
        <small id="error-nombre" class="error-text"></small>
      </div>

      <!-- Estado -->
      <div class="input-group-nowrap">
        <label for="estado" class="form-label">Estado</label>
        <div class="input-contenedor">
          <i class="ri-focus-2-line" id="input-icono-estado"></i>
          <select id="estado" name="estado" class="form-select">
            <option value="1" <?= $categoria->getEstado() ? 'selected' : '' ?>>Habilitado</option>
            <option value="0" <?= !$categoria->getEstado() ? 'selected' : '' ?>>Deshabilitado</option>
          </select>
          <i id="input-estado-estado" class="input-estado"></i>
        </div>
        <small id="error-estado" class="error-text"></small>
      </div>

      <!-- Descripción -->
      <div class="input-group-nowrap">
        <label for="descripcion" class="form-label">Descripción</label>
        <div class="input-contenedor">
          <i class="ri-file-text-line" id="input-icono-descripcion"></i>
          <textarea id="descripcion" name="descripcion" rows="3" placeholder="Describe brevemente..."><?= htmlspecialchars($categoria->getDescripcion()) ?></textarea>
          <i id="input-estado-descripcion" class="input-estado"></i>
        </div>
        <small id="error-descripcion" class="error-text"></small>
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

              <span class="info-imagen" id="nombre-archivo" title="<?= $categoria->getImagen() ?>"><?= $categoria->getImagen() ?: 'Ningún archivo seleccionado' ?></span>

              <i class="ri-image-add-line upload-icon" id="upload-icon" style="display: none;"></i>
              <p id="preview-texto" style="<?= $categoria->getImagen() ? 'display: none;' : 'display: block;' ?>">
                Arrastre una imagen aquí<br>
                o<br>
                selecciónelo desde su dispositivo.
              </p>
            <?php else : ?>
              <img id="preview-imagen" src="" alt="Vista previa vacía" draggable="false" style="display: none;">
              <i class="ri-file-close-line upload-icon" id="upload-icon" style="display: block;"></i>
              <p id="preview-texto" style="display: block;">Imagen no disponible o eliminada</p>
            <?php endif; ?>
          </div>
          <small id="error-imagen" class="error-text"></small>
          <!-- Footer con archivo, botón quitar y botón subir imagen -->
          <div class="upload-footer">
            <!-- Nombre del archivo -->
            <label for="imagen" class="btn-image boton-subir-imagen" title="Subir nueva imagen">
              <i class="ri-upload-cloud-2-fill"></i>
              <span>Subir imagen</span>
            </label>
            <!-- Botón: quitar imagen -->
            <?php if ($categoria->getImagen()) : ?>
              <button type="button" id="btn-quitar-imagen" class="btn-image boton-quitar-imagen" title="Quitar imagen actual">
                <i class="ri-delete-bin-2-fill"></i>
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
    <a href="<?= BASE_URL ?>categoria/" class="boton-form boton-volver">
      <i class="ri-arrow-left-circle-fill"></i> Volver
    </a>
    <button type="submit" class="boton-form boton-actualizar">
      <i class="ri-loop-left-line"></i> Actualizar
    </button>
  </div>
</form>
<script type="module">
    import { validarCampo, validarFormulario, validarImagen } from '<?= BASE_URL ?>public/js/validaciones-generales.js';

    const campos = [
        { id: 'nombre', iconoId: 'input-icono-nombre', estadoId: 'input-estado-nombre', errorId: 'error-nombre', label: 'Nombre', min: 3, max: 50 },
        { id: 'descripcion', iconoId: 'input-icono-descripcion', estadoId: 'input-estado-descripcion', errorId: 'error-descripcion', label: 'Descripción', min: 5, max: 150 },
        { id: 'estado', iconoId: 'input-icono-estado', estadoId: 'input-estado-estado', errorId: 'error-estado', label: 'Estado', esSelect: true }
    ];

    campos.forEach(validarCampo);
    validarFormulario({ formSelector: '.formulario', campos });
</script>
<?php
modalFlash($mensaje);
footerAdmin();
?>