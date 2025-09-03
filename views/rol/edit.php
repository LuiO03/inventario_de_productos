<?php
headerAdmin();
partialBreadcrumb();
?>

<div class="contenedor-header">
  <h1>#<?= htmlspecialchars($rol->getId()) ?> - <?= htmlspecialchars($rol->getNombre()) ?></h1>
  <p>Editar <strong>Rol</strong></p>
</div>

<form action="<?= BASE_URL ?>rol/update/<?= urlencode($rol->getId()) ?>" method="post" class="formulario" autocomplete="off">
  <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF(); ?>">
  <input type="hidden" name="id" value="<?= htmlspecialchars($rol->getId()) ?>">
  <small class="form-aviso">
    Los campos con asterisco (<span class="text-primario"><i class="ri-asterisk"></i></span>) son obligatorios.
  </small>
  <div class="formulario-contenido">
    <div class="formulario-columna">
      <?php alertValidate(); ?>
      <!-- Nombre -->
      <div class="input-group">
        <label for="nombre" class="form-label">
          Nombre del Rol<small class="text-primario"><i class="ri-asterisk"></i></small>
        </label>
        <div class="input-contenedor">
          <i class="ri-shield-user-line" id="input-icono-nombre"></i>
          <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($rol->getNombre()) ?>" required placeholder="Ingrese el nombre del rol">
          <i id="input-estado-nombre" class="input-estado"></i>
        </div>
        <small id="error-nombre" class="error-text"></small>
      </div>

      <!-- Estado -->
      <div class="input-group">
        <label for="estado" class="form-label">
          Estado<small class="text-primario"><i class="ri-asterisk"></i></small>
        </label>
        <div class="input-contenedor">
          <i class="ri-focus-2-line" id="input-icono-estado"></i>
          <select id="estado" name="estado" class="form-select">
            <option value="1" <?= $rol->getEstado() ? 'selected' : '' ?>>Habilitado</option>
            <option value="0" <?= !$rol->getEstado() ? 'selected' : '' ?>>Deshabilitado</option>
          </select>
          <i id="input-estado-estado" class="input-estado select"></i>
        </div>
        <small id="error-estado" class="error-text"></small>
      </div>

      <!-- Descripción -->
      <div class="input-group">
        <label for="descripcion" class="form-label">
          Descripción
        </label>
        <div class="input-contenedor">
          <i class="ri-file-text-line" id="input-icono-descripcion"></i>
          <textarea id="descripcion" name="descripcion" rows="3" placeholder="Describe brevemente..."><?= htmlspecialchars($rol->getDescripcion()) ?></textarea>
          <i id="input-estado-descripcion" class="input-estado"></i>
        </div>
        <small id="error-descripcion" class="error-text"></small>
      </div>
    </div>
  </div>

  <div class="formulario-acciones edit">
    <a href="<?= BASE_URL ?>rol/" class="boton-form boton-volver">
      <i class="ri-arrow-left-circle-fill"></i> Volver
    </a>
    <button type="submit" class="boton-form boton-actualizar">
      <i class="ri-loop-left-line"></i> Actualizar
    </button>
  </div>
</form>

<script type="module">
    import { validarCampo, validarFormulario } from '<?= BASE_URL ?>public/js/validaciones-generales.js';

    const campos = [
        { id: 'nombre', iconoId: 'input-icono-nombre', estadoId: 'input-estado-nombre', errorId: 'error-nombre', label: 'Nombre', min: 3, max: 50 },
        { id: 'descripcion', iconoId: 'input-icono-descripcion', estadoId: 'input-estado-descripcion', errorId: 'error-descripcion', label: 'Descripción', min: 0, max: 150 },
        { id: 'estado', iconoId: 'input-icono-estado', estadoId: 'input-estado-estado', errorId: 'error-estado', label: 'Estado', esSelect: true }
    ];

    campos.forEach(validarCampo);
    validarFormulario({ formSelector: '.formulario', campos });
</script>

<?php
modalFlash($mensaje);
footerAdmin();
?>
