<?php
    headerAdmin();
    partialBreadcrumb();
?>

<!-- Contenido de la informacion y botones -->
<div class="contenedor-header">
    <h1 class="text-center">Lista de Productos</h1>
    <p class="text-center">Esta es la página de productos.</p>
    <div class="contenedor-botones">
        <div class="button-borders">
            <button class="btn-export btn-copy" title="Copiar Registros"> <i class="ri-file-copy-line"></i> Copiar </button>
        </div>
        <div class="button-borders">
            <button class="btn-export btn-excel" title="Exportar Excel"> <i class="ri-file-excel-2-line"></i> Excel </button>
        </div>
        <div class="button-borders">
            <button class="btn-export btn-pdf" title="Exportar PDF"> <i class="ri-file-pdf-2-line"></i> PDF </button>
        </div>
        <div class="button-borders">
            <button class="btn-export btn-print" title="Imprimir Tabla"> <i class="ri-printer-line"></i> Imprimir </button>
        </div>
    </div>
</div>


<!-- Contenido de la tabla -->
<div class="contenedor">
    <table id="tablaProductos" class="table-sm w-100 tabla-responsive">
        <thead>
            <tr>
                <th class="text-start">ID</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Precio</th>
                <th class="text-center">Stock</th>
                <th class="text-center">Opciones</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td data-label="ID:"><?= htmlspecialchars($producto->getId()) ?></td>
                        <td data-label="Nombre:"><?= htmlspecialchars($producto->getNombre()) ?></td>
                        <td class="" data-label="Precio:">S/. <?= htmlspecialchars($producto->getPrecio()) ?></td>
                        <td class="" data-label="Stock:"><?= htmlspecialchars($producto->getStock()) ?></td>
                        <td>
                            <div class="table-botones">
                                <button title="Ver Producto"
                                    class="btn btn-sm btn-info btn-ver-producto"
                                    data-id="<?= $producto->getId() ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewModal">
                                    <span class="boton-text">Ver</span>
                                    <span class="boton-icon"><i class="ri-eye-2-fill"></i></span>
                                </button>
                                <a href="<?= BASE_URL ?>producto/edit/<?= $producto->getId() ?>" title="Editar Producto" class="btn btn-sm btn-warning">
                                    <span class="boton-icon"><i class="ri-edit-circle-fill"></i></span>
                                    <span class="boton-text">Editar</span>
                                </a>
                                <button type="button" title="Eliminar Producto" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $producto->getId() ?>">
                                    <span class="boton-text">Borrar</span>
                                    <span class="boton-icon"><i class="ri-delete-bin-2-fill"></i></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

    </table>
</div>

<!-- Modal ver registro -->
<div class="modal fade modal-slide-animate" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger border-0">
                <h6 class="modal-title fw-bold" id="viewModalLabel">Detalles del Producto </span></h6>
                <button type="button" class="btn-close btn-close-white bg-white rounded-circle p-2" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table-sm w-100 ">
                        <thead>
                            <tr>
                                <th class="text-center">Atributos</th>
                                <th class="text-center">Datos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="w-50">ID:</td>
                                <td class="text-center"><span id="modal-id"></span></td>
                            </tr>
                            <tr>
                                <td>Nombre:</td>
                                <td class="text-center"><span id="modal-nombre"></span></td>
                            </tr>
                            <tr>
                                <td>Precio:</td>
                                <td class="text-center">S/. <span id="modal-precio"></span></td>
                            </tr>
                            <tr>
                                <td>Stock:</td>
                                <td class="text-center"><span id="modal-stock"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal"><i class="ri-close-line"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php
    menuFlotante();
    modalFlash($mensaje);
    modalConfirmacion();
    footerAdmin();
?>