<?php
headerAdmin();
partialBreadcrumb();
?>

<div class="contenedor-header">
    <h1>Agregar Marca</h1>
    <p>Aquí puedes agregar una nueva marca para los productos.</p>
</div>

<form action="<?= BASE_URL ?>marca/store" method="post" enctype="multipart/form-data" class="formulario" autocomplete="off">
    <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF(); ?>">

    <div class="formulario-contenido">
        <div class="formulario-columna-nowrap">
            <?php alertValidate(); ?>
            <!-- Nombre -->
            <div class="input-group-nowrap">
                <label for="nombre" class="form-label">Nombre de la Marca</label>
                <div class="input-icono">
                    <i class="ri-price-tag-3-line"></i>
                    <input type="text" id="nombre" name="nombre" required placeholder="Ingrese el nombre" value="<?= htmlspecialchars($old['nombre'] ?? '') ?>">
                </div>
            </div>

            <!-- Estado -->
            <div class="input-group-nowrap">
                <label for="estado" class="form-label">Estado</label>
                <div class="input-icono">
                    <i class="ri-focus-2-line"></i>
                    <select id="estado" name="estado" class="form-select" required>
                        <option value="">Seleccione un estado</option>
                        <option value="1" <?= (isset($old['estado']) && $old['estado'] == '1') ? 'selected' : '' ?>>Habilitado</option>
                        <option value="0" <?= (isset($old['estado']) && $old['estado'] == '0') ? 'selected' : '' ?>>Deshabilitado</option>
                    </select>
                </div>
            </div>

            <!-- Descripción -->
            <div class="input-group-nowrap">
                <label for="descripcion" class="form-label">Descripción</label>
                <div class="input-icono">
                    <i class="ri-file-text-line"></i>
                    <textarea id="descripcion" name="descripcion" rows="3" placeholder="Describe brevemente..."><?= htmlspecialchars($old['descripcion'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <div class="formulario-columna-nowrap">
            <!-- Imagen -->
            <div class="input-group-nowrap">
                <label class="form-label">Imagen de la marca</label>
                <div class="contenedor-upload">
                    <div class="upload-header" id="preview-container">
                        <img id="preview-imagen" src="" alt="Vista previa vacía" draggable="false" style="display: none;">
                        <i class="ri-image-add-line upload-icon" id="upload-icon" style="display: block;"></i>
                        <p id="preview-texto" style="display: block;">
                            ¡Arrastre una imagen aquí!<br>o selecciónelo desde su dispositivo.
                        </p>
                    </div>

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
        <a href="<?= BASE_URL ?>marca/" class="boton-form boton-volver">
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

<script>
    document.getElementById('btnLimpiar').addEventListener('click', () => {
        document.getElementById('nombre').value = '';
        document.getElementById('descripcion').value = '';
        document.getElementById('estado').selectedIndex = 0;
        inputImagen.value = '';
        imgPreview.src = '';
        imgPreview.style.display = 'none';
        icono.style.display = 'block';
        texto.style.display = 'block';
    });
</script>

<?php
modalFlash($mensaje);
footerAdmin();
?>
