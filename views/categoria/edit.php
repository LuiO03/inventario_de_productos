<?php
headerAdmin();
partialBreadcrumb();
?>

<div class="contenedor-header">
  <h1>
    Editar Categoría #<?= htmlspecialchars($categoria->getId()) ?>
  </h1>
  <p class="fw-bolder">
    <?= htmlspecialchars($categoria->getNombre()) ?>
  </p>
</div>

<form action="<?= BASE_URL ?>categoria/update/<?= $categoria->getId() ?>" method="post" enctype="multipart/form-data"
  class="formulario" autocomplete="off">
  <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF(); ?>">
  <input type="hidden" name="id" value="<?= htmlspecialchars($categoria->getId()) ?>">
  <small class="form-aviso">
    Los campos con asterisco (<span class="text-primario"><i class="ri-asterisk"></i></span>) son obligatorios.
  </small>
  <div class="formulario-contenido">
    <!-- Columna de inputs -->
    <div class="formulario-columna-nowrap">
      <?php alertValidate(); ?>
      <!-- Nombre -->
      <div class="input-group-nowrap">
        <label for="nombre" class="form-label">
          Nombre de la Categoría<small class="text-primario"><i class="ri-asterisk"></i></small>
        </label>
        <div class="input-contenedor">
          <i class="ri-price-tag-3-line" id="input-icono-nombre"></i>
          <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($categoria->getNombre()) ?>" required
            placeholder="Ingrese el nombre">
          <i id="input-estado-nombre" class="input-estado"></i>
        </div>
        <small id="error-nombre" class="error-text"></small>
      </div>

      <!-- Estado -->
      <div class="input-group-nowrap">
        <label for="estado" class="form-label">Estado<small class="text-primario"><i class="ri-asterisk"></i></small></label>
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
        <label for="descripcion" class="form-label">Descripción<small class="text-primario"><i class="ri-asterisk"></i></small></label>
        <div class="input-contenedor">
          <i class="ri-file-text-line" id="input-icono-descripcion"></i>
          <textarea id="descripcion" name="descripcion" rows="3"
            placeholder="Describe brevemente..."><?= htmlspecialchars($categoria->getDescripcion()) ?></textarea>
          <i id="input-estado-descripcion" class="input-estado"></i>
        </div>
        <small id="error-descripcion" class="error-text"></small>
      </div>
      <!-- Subir nueva imagen o mostrar actual -->
      <div class="input-group-nowrap">

        <label class="form-label">Imagen de la categoría</label>
        <div class="contenedor-upload">
          <div class="upload-header" id="preview-container">
            <?php if ($categoria->getImagen()): ?>
              <img id="preview-imagen" src="<?= BASE_URL . 'public/images/categorias/' . $categoria->getImagen() ?>"
                alt="Vista previa de la imagen" draggable="false" style="display: block;">

              <span class="info-imagen" id="nombre-archivo"
                title="<?= $categoria->getImagen() ?>"><?= $categoria->getImagen() ?: 'Ningún archivo seleccionado' ?></span>

              <i class="ri-image-add-line upload-icon" id="upload-icon" style="display: none;"></i>
              <p id="preview-texto" style="<?= $categoria->getImagen() ? 'display: none;' : 'display: block;' ?>">
                Arrastre una imagen aquí<br>
                o<br>
                selecciónelo desde su dispositivo.
              </p>
            <?php else: ?>
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
            <?php if ($categoria->getImagen()): ?>
              <button type="button" id="btn-quitar-imagen" class="btn-image boton-quitar-imagen"
                title="Quitar imagen actual">
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

    <div class="formulario-columna-nowrap">
      <!-- Categoría Padre -->
      <div class="input-group-nowrap">
        <label for="parent_id" class="form-label">Categoría Padre</label>
        <div class="input-contenedor">
          <i class="ri-folder-open-line" id="input-icono-parent_id"></i>
          <select id="parent_id" name="parent_id" class="form-select">
            <option value="">Ninguna (Categoría principal)</option>
            <?php foreach ($categorias as $cat): ?>
              <option value="<?= $cat->getId() ?>" <?= ($categoria->getParentId() == $cat->getId()) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat->getNombre()) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <i id="input-estado-parent_id" class="input-estado"></i>
        </div>
        <small id="error-parent_id" class="error-text"></small>
      </div>

      <!-- Subcategorías -->
      <div class="input-group-nowrap">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <label class="form-label mb-0">Lista de subcategorías</label>
          <a href="<?= BASE_URL ?>categoria/create/<?= urlencode($cat->getSlug() ?: $cat->getId()) ?>" class="boton btn-primary">
            <span class="boton-icon"><i class="ri-add-circle-fill"></i></span>
            <span class="boton-text">Agregar Subcategoría</span>
          </a>
        </div>
        <table id="tablaSubcategorias" class="table-sm w-100 tabla-responsive">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Estado</th>
              <th class="text-center">Opciones</th>
            </tr>
          </thead>

          <tbody>
            <?php if (!empty($subcategorias)): ?>
              <?php foreach ($subcategorias as $subcategoria): ?>
                <tr>
                  <td data-label="ID:"><?= htmlspecialchars($subcategoria->getId()) ?></td>
                  <td class="text-start" data-label="Nombre:"><?= htmlspecialchars($subcategoria->getNombre()) ?></td>
                  <td data-label="Estado:">
                    <label class="switch-tabla">
                      <input type="checkbox" id="switch-categoria-<?= $subcategoria->getId() ?>" class="toggle-estado"
                        data-id="<?= $subcategoria->getId() ?>" <?= $subcategoria->getEstado() ? 'checked' : '' ?>>
                      <span class="slider"></span>
                    </label>
                  </td>

                  <td>
                    <div class="table-botones">
                      <button class="btn-ver-categoria btn btn-sm btn-info" data-id="<?= $subcategoria->getId() ?>"
                        title="Ver Categoría" type="button">
                        <span class="boton-text">Ver</span>
                        <span class="boton-icon"><i class="ri-eye-2-fill"></i></span>
                      </button>
                      <a href="<?= BASE_URL ?>categoria/edit/<?= urlencode($subcategoria->getSlug()) ?>"
                        title="Editar Categoría" class="btn btn-sm btn-warning">
                        <span class="boton-text">Editar</span>
                        <span class="boton-icon"><i class="ri-edit-circle-fill"></i></span>
                      </a>
                      <button type="button" title="Eliminar Categoría" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteModal" data-id="<?= $subcategoria->getId() ?>">
                        <span class="boton-text">Eliminar</span>
                        <span class="boton-icon"><i class="ri-delete-bin-2-fill"></i></span>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4">
                  <div class="d-flex flex-column align-items-center justify-content-center py-3">
                    <i class="ri-price-tag-3-fill fs-1 mb-2"></i>
                    <span>No hay subcategorías registradas.</span>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
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
  import {
    validarCampo,
    validarFormulario,
    validarImagen
  } from '<?= BASE_URL ?>public/js/validaciones-generales.js';
  const campos = [{
      id: 'nombre',
      iconoId: 'input-icono-nombre',
      estadoId: 'input-estado-nombre',
      errorId: 'error-nombre',
      label: 'Nombre',
      min: 3,
      max: 50
    },
    {
      id: 'descripcion',
      iconoId: 'input-icono-descripcion',
      estadoId: 'input-estado-descripcion',
      errorId: 'error-descripcion',
      label: 'Descripción',
      min: 5,
      max: 150
    },
    {
      id: 'estado',
      iconoId: 'input-icono-estado',
      estadoId: 'input-estado-estado',
      errorId: 'error-estado',
      label: 'Estado',
      esSelect: true
    }
  ];

  campos.forEach(validarCampo);
  validarFormulario({
    formSelector: '.formulario',
    campos
  });

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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-ver-categoria').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;

                fetch(`${'<?= BASE_URL ?>'}categoria/show/${id}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Error al obtener datos');
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('modal-id').textContent = data.id || '-';
                        document.getElementById('modal-nombre').textContent = data.nombre || '-';
                        document.getElementById('modal-descripcion').textContent = data.descripcion || '(Sin descripción)';
                        document.getElementById('modal-estado').innerHTML = data.estado ?
                            '<span class="medalla bg-success"><i class="ri-eye-line"></i> Habilitado</span>' :
                            '<span class="medalla bg-secondary"><i class="ri-eye-off-line"></i> Deshabilitado</span>';
                        document.getElementById('modal-creado-por').textContent = data.creado_por ?? '-';
                        document.getElementById('modal-modificado-por').textContent = data.modificado_por ?? '-';
                        document.getElementById('modal-created-at').textContent = data.created_at || '-';
                        document.getElementById('modal-updated-at').textContent = data.updated_at || '-';

                        const contenedorSubcategorias = document.getElementById('modal-subcategorias');
                        contenedorSubcategorias.innerHTML = ''; // Limpiar antes

                        if (data.subcategorias && data.subcategorias.length > 0) {
                            data.subcategorias.forEach(sub => {
                                const li = document.createElement('li');
                                li.classList.add(sub.estado ? 'text-primario' : 'text-muted'); // Aplica clase global
                                li.classList.add('text-md');

                                const icono = sub.estado ? 'ri-eye-fill' : 'ri-eye-close-fill';

                                li.innerHTML = `
                                    <i class="${icono} me-1"></i> <span>#${sub.id} -</span>
                                    ${sub.nombre}
                                `;
                                contenedorSubcategorias.appendChild(li);
                            });
                        } else {
                            contenedorSubcategorias.innerHTML = `
                                <li class="medalla bg-secondary text-white">
                                    <i class="ri-price-tag-line"></i>Sin subcategorías
                                </li>
                            `;
                        }

                        const padre = document.getElementById('modal-categoria-padre');
                        if (data.categoria_padre) {
                            const claseEstado = data.categoria_padre.estado ? 'text-primario' : 'text-muted';
                            padre.classList.add(claseEstado);
                            const icono = data.categoria_padre.estado ? 'ri-eye-fill' : 'ri-eye-close-fill';
                            padre.innerHTML = `
                            <i class="${icono} me-1 ${claseEstado}"></i> 
                            <span>#${data.categoria_padre.id} -</span> 
                            ${data.categoria_padre.nombre} `;
                        } else {
                            padre.innerHTML = `
                            <li class="medalla bg-secondary text-white">
                                <i class="ri-price-tag-2-line"></i>Sin categoría padre
                            </li>`;
                        }
                        const img = document.getElementById('modal-imagen');
                        const sinImagen = document.getElementById('modal-sin-imagen');

                        if (data.imagen_url) {
                            img.src = data.imagen_url;
                            img.classList.remove('d-none');
                            sinImagen.classList.add('d-none');
                        } else {
                            img.classList.add('d-none');
                            sinImagen.classList.remove('d-none');
                        }
                        /* esto es para # mostrar el modal */
                        const viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
                        viewModal.show();
                    })
                    .catch(error => {
                        console.error(error);
                        document.getElementById('modal-id').textContent = '-';
                        document.getElementById('modal-nombre').textContent = 'Error';
                        document.getElementById('modal-descripcion').textContent = '-';
                        document.getElementById('modal-estado').innerHTML = '<span class="medalla bg-danger">Error</span>';
                    });
            });
        });
        document.querySelectorAll('.toggle-estado').forEach(input => {
            input.addEventListener('change', function() {
                const id = this.dataset.id;
                const nuevoEstado = this.checked ? 1 : 0;

                fetch(`<?= BASE_URL ?>categoria/toggleEstado/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            estado: nuevoEstado
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            alert('Error al actualizar el estado');
                            this.checked = !nuevoEstado; // revertir si falla
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert('Error al actualizar el estado');
                        this.checked = !nuevoEstado;
                    });
            });
        });
    });
</script>
<!-- Modal ver registro -->
<div class="modal fade modal-slide-animate" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-bold" id="viewModalLabel">Detalles de la Categoría</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <h2 class="modal-nombre" id="modal-nombre"></h2>
                <table class="table-sm w-100">
                    <tbody>
                        <tr>
                            <td class="text-start fw-bolder px-2">ID:</td>
                            <td class="text-start px-2"><span id="modal-id"></span></td>
                        </tr>
                        <tr>
                            <td class="text-start fw-bolder px-2">Descripción:</td>
                            <td class="text-start px-2"><span id="modal-descripcion"></span></td>
                        </tr>
                        <tr>
                            <td class="text-start fw-bolder px-2">Estado:</td>
                            <td class="text-start px-2" class=""><span id="modal-estado"></span></td>
                        </tr>
                        <tr>
                            <td class="text-start fw-bolder px-2">Categoría Padre:</td>
                            <td class="text-start px-2" id="modal-categoria-padre">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start fw-bolder px-2">Subcategorías:</td>
                            <td class="text-start px-2">
                                <ul id="modal-subcategorias" class="mb-0 px-0 small"></ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start fw-bolder px-2">Creado por:</td>
                            <td class="text-start px-2">
                                <span id="modal-creado-por"></span>
                                <div>
                                    <i class="ri-calendar-schedule-fill text-primario"></i>
                                    <span class="text-primario" id="modal-created-at"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start fw-bolder px-2">Modificado por:</td>
                            <td class="text-start px-2">
                                <span id="modal-modificado-por"></span>
                                <div>
                                    <i class="ri-calendar-schedule-fill text-primario"></i>
                                    <span class="text-primario" id="modal-updated-at"></span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <img id="modal-imagen" class="img-thumbnail d-none modal-imagen" alt="Imagen Marca">
                    <div id="modal-sin-imagen" class="modal-sin-imagen d-none">
                        <i class="ri-landscape-fill"></i>
                        <span>Sin imagen</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center mt-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<?php
modalConfirmacion();
footerAdmin();
?>