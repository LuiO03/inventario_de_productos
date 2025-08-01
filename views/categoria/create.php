<?php
headerAdmin();
partialBreadcrumb();
?>

<div class="contenedor-header">
    <?php if ($parentId): ?>
        <h1>#<?= htmlspecialchars($parentId) ?> - <?= htmlspecialchars($nombrePadre) ?></h1>
        <p>Agregar <strong>Subcategoría</strong></p>
    <?php else: ?>
        <h1>Agregar Categoría</h1>
        <p>Aquí puedes agregar una nueva <strong>categoría</strong> para los productos.</p>
    <?php endif; ?>
</div>

<form action="<?= BASE_URL ?>categoria/store" method="post" enctype="multipart/form-data" class="formulario" autocomplete="off">
    <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF(); ?>">
    <small class="form-aviso">
        Los campos con asterisco (<span class="text-primario"><i class="ri-asterisk"></i></span>) son obligatorios.
    </small>

    <div class="formulario-contenido">
        <div class="formulario-columna-nowrap">
            <?php alertValidate(); ?>

            <!-- Categoría Padre -->
            <?php if ($parentId): ?>
                <input type="hidden" name="parent_id" value="<?= htmlspecialchars($parentId) ?>">
            <?php else: ?>
                <div class="input-group-nowrap">
                    <label for="parent_id" class="form-label">Categoría Padre</label>
                    <div class="input-contenedor">
                        <i class="ri-folder-open-line" id="input-icono-parent_id"></i>
                        <select id="parent_id" name="parent_id" class="form-select">
                            <option value="">Ninguna (Categoría principal)</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat->getId() ?>" <?= (isset($old['parent_id']) && $old['parent_id'] == $cat->getId()) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat->getNombre()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <i id="input-estado-parent_id" class="input-estado"></i>
                    </div>
                    <small id="error-parent_id" class="error-text"></small>
                </div>
            <?php endif; ?>

            <!-- Nombre -->
            <div class="input-group-nowrap">
                <label for="nombre" class="form-label">
                    Nombre de la Categoría<small class="text-primario"><i class="ri-asterisk"></i></small>
                </label>
                <div class="input-contenedor">
                    <i class="ri-price-tag-3-line" id="input-icono-nombre"></i>
                    <input type="text" id="nombre" name="nombre" required placeholder="Ingrese el nombre" value="<?= htmlspecialchars($old['nombre'] ?? '') ?>">
                    <i id="input-estado-nombre" class="input-estado"></i>
                </div>
                <small id="error-nombre" class="error-text"></small>
            </div>

            <!-- Estado -->
            <div class="input-group-nowrap">
                <label for="estado" class="form-label">
                    Estado<small class="text-primario"><i class="ri-asterisk"></i></small>
                </label>
                <div class="input-contenedor">
                    <i class="ri-focus-2-line" id="input-icono-estado"></i>
                    <select id="estado" name="estado" class="form-select" required>
                        <option value="">Seleccione un estado</option>
                        <option value="1" <?= (isset($old['estado']) && $old['estado'] == '1') ? 'selected' : '' ?>>Habilitado</option>
                        <option value="0" <?= (isset($old['estado']) && $old['estado'] == '0') ? 'selected' : '' ?>>Deshabilitado</option>
                    </select>
                    <i id="input-estado-estado" class="input-estado"></i>
                </div>
                <small id="error-estado" class="error-text"></small>
            </div>

            <!-- Descripción -->
            <div class="input-group-nowrap">
                <label for="descripcion" class="form-label">
                    Descripción<small class="text-primario"><i class="ri-asterisk"></i></small>
                </label>
                <div class="input-contenedor">
                    <i class="ri-file-text-line" id="input-icono-descripcion"></i>
                    <textarea id="descripcion" name="descripcion" rows="3" placeholder="Describe brevemente..."><?= htmlspecialchars($old['descripcion'] ?? '') ?></textarea>
                    <i id="input-estado-descripcion" class="input-estado"></i>
                </div>
                <small id="error-descripcion" class="error-text"></small>
            </div>
        </div>

        <div class="formulario-columna-nowrap">
            <!-- Imagen -->
            <div class="input-group-nowrap">
                <label class="form-label">Imagen de la categoría</label>
                <div class="contenedor-upload">
                    <div class="upload-header" id="preview-container">
                        <img id="preview-imagen" src="" alt="Vista previa vacía" draggable="false" style="display: none;">
                        <i class="ri-image-add-line upload-icon" id="upload-icon" style="display: block;"></i>
                        <p id="preview-texto" style="display: block;">
                            ¡Arrastre una imagen aquí!<br>o selecciónelo desde su dispositivo.
                        </p>
                    </div>
                    
                    <small id="error-imagen" class="error-text"></small>
                    <div class="upload-footer">
                        <label for="imagen" class="btn-image boton-subir-imagen" title="Subir imagen">
                            <i class="ri-upload-cloud-2-fill"></i> <span>Subir imagen</span>
                        </label>
                        <input type="file" name="imagen" id="imagen" accept="image/*">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="formulario-acciones">
        <a href="<?= BASE_URL ?>categoria/" class="boton-form boton-volver">
            <i class="ri-arrow-left-circle-fill"></i> Volver
        </a>
        <button type="button" class="boton-form boton-limpiar" id="btnLimpiar">
            <i class="ri-paint-brush-fill"></i> Limpiar
        </button>
        <button type="submit" class="boton-form boton-agregar">
            <i class="ri-save-3-fill"></i> Guardar
        </button>
    </div>
</form>

<script type="module">
    import { validarCampo, validarFormulario, validarImagen } from '<?= BASE_URL ?>public/js/validaciones-generales.js';

    const campos = [
        { id: 'nombre', iconoId: 'input-icono-nombre', estadoId: 'input-estado-nombre', errorId: 'error-nombre', label: 'Nombre', min: 3, max: 50 },
        { id: 'descripcion', iconoId: 'input-icono-descripcion', estadoId: 'input-estado-descripcion', errorId: 'error-descripcion', label: 'Descripción', min: 5, max: 150 },
        { id: 'estado', iconoId: 'input-icono-estado', estadoId: 'input-estado-estado', errorId: 'error-estado', label: 'Estado', esSelect: true },
    ];

    campos.forEach(validarCampo);
    validarFormulario({ formSelector: '.formulario', campos });

    $(document).ready(function () {
      $('#parent_id').select2({
        allowClear: false,
        language: {
            noResults: function () {
            return "No hay coincidencias...";
            }
        },
        width: '100%' // se adapta al estilo del contenedor
      });
  });
</script>

<?php
modalFlash($mensaje);
footerAdmin();
?>
