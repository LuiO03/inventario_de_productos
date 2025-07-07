<?php
headerAdmin();
partialBreadcrumb();
?>

<div class="contenedor-header">
    <h1 class="text-center">
        Agregar Categoría
    </h1>
    <p class="text-center">
        Aquí puedes agregar una nueva categoría para los productos.
    </p>
</div>

<!-- IMPORTANTE: enctype="multipart/form-data" para subir archivos -->
<form action="<?= BASE_URL ?>categoria/store" method="post" enctype="multipart/form-data" class="formulario" autocomplete="off">

    <div class="formulario-contenido">
        <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF(); ?>">
        <div class="formulario-columna-nowrap">
            <!-- Nombre -->
            <div class="input-group-nowrap">
                <label for="nombre" class="form-label">Nombre de la Categoría</label>
                <div class="input-icono">
                    <i class="ri-price-tag-3-line"></i>
                    <input type="text" id="nombre" name="nombre" required placeholder="Ingrese el nombre">
                </div>
            </div>

            <!-- Estado -->
            <div class="input-group-nowrap input-group-nowrap-small">
                <label for="estado" class="form-label">Estado</label>
                <div class="input-icono">
                    <i class="ri-focus-2-line"></i>
                    <select id="estado" name="estado" class="form-select" required>
                        <option value="">Seleccione un estado</option>
                        <option value="1">Habilitado</option>
                        <option value="0">Deshabilitado</option>
                    </select>
                </div>
            </div>

            <!-- Descripción -->
            <div class="input-group-nowrap">
                <label for="descripcion" class="form-label">Descripción</label>
                <div class="input-icono">
                    <i class="ri-file-text-line"></i>
                    <textarea id="descripcion" name="descripcion" rows="3" placeholder="Describe brevemente..."></textarea>
                </div>
            </div>
        </div>

        <div class="formulario-columna-nowrap">
            <!-- Subir nueva imagen o mostrar actual -->
            <div class="input-group-nowrap-nowrap">

                <label class="form-label">Imagen de la categoría</label>

                <div class="contenedor-upload">
                    <div class="upload-header" id="preview-container">
                        <img id="preview-imagen" src="" alt="Vista previa vacía" draggable="false" style="display: none;">
                        <i class="ri-image-add-line upload-icon" id="upload-icon" style="display: block;"></i>
                        <p id="preview-texto" style="display: block;">
                            ¡Arrastre una imagen aquí! <br>
                            o seleccionelo desde su dispositivo.
                        </p>
                    </div>

                    <!-- Footer -->
                    <div class="upload-footer">
                        <label for="imagen" class="info-imagen" title="Ningún archivo seleccionado">
                            <i class="ri-upload-2-line"></i>
                            <p id="nombre-archivo">Ningún archivo seleccionado</p>
                            <i class="ri-folder-open-line"></i>
                        </label>

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
        <button type="button" class="boton-form boton-limpiar" id="btnLimpiar">
            <i class="ri-paint-brush-fill"></i> Limpiar
        </button>
        <button type="submit" class="boton-form boton-agregar">
            <i class="ri-save-3-fill"></i> Guardar Categoría
        </button>
    </div>

    <script>
        document.getElementById('btnLimpiar').addEventListener('click', () => {
            document.getElementById('nombre').value = '';
            document.getElementById('descripcion').value = '';
            document.getElementById('estado').selectedIndex = 0;
            document.getElementById('imagen').value = null;
        });
    </script>
</form>

<?php
modalFlash($mensaje);
footerAdmin();
?>