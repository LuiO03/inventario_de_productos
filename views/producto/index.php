<?php headerAdmin(); ?>

<nav aria-label="breadcrumb" class="breadcrumb-container text-xs">
    <ol class="breadcrumb">
        <li>
            <a href="<?= BASE_URL ?>">
                <i class="ri-home-4-line"></i> Inicio
            </a>
            <i class="ri-arrow-right-s-line separator"></i>
        </li>
        <li>
            <a href="<?= BASE_URL ?>producto">
                <i class="ri-t-shirt-line"></i> Productos
            </a>
            <i class="ri-arrow-right-s-line separator"></i>
        </li>
        <li class="active">
            <i class="ri-list-check-2"></i> Lista
        </li>
    </ol>
</nav>

<h1 class="text-center">Lista de Productos</h1>
<p class="text-center">Esta es la página de productos.</p>

<div class="d-flex justify-content-center align-items-center flex-wrap gap-3">
    <a href="<?= BASE_URL ?>producto/create" class="btn btn-sm btn-success d-flex align-items-center gap-2">
        <i class="ri-add-line fs-5"></i> Agregar Producto
    </a>
    <a href="<?= BASE_URL ?>" class="btn btn-sm btn-primary d-flex align-items-center gap-2">
        <i class="ri-home-line fs-5"></i> Inicio
    </a>
</div>

<div class="contenedor">
    <table id="tablaProductos" class="col-12 table-striped table-sm d-none">
        <thead>
            <tr>
                <th class="text-center">ID</th>
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
                        <td class="text-center"><?= htmlspecialchars($producto->getId()) ?></td>
                        <td><?= htmlspecialchars($producto->getNombre()) ?></td>
                        <td class="text-center">S/. <?= htmlspecialchars($producto->getPrecio()) ?></td>
                        <td class="text-center"><?= htmlspecialchars($producto->getStock()) ?></td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">

                                <button
                                    class="btn btn-sm btn-info btn-ver-producto"
                                    data-id="<?= $producto->getId() ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewModal">
                                    <i class="ri-eye-line"></i> Ver
                                </button>
                                <a href="<?= BASE_URL ?>producto/edit/<?= $producto->getId() ?>" title="Editar Usuario" class="btn btn-sm btn-warning">
                                    <i class="ri-edit-line"></i> Editar
                                </a>
                                <button type="button" title="Eliminar Usuario" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $producto->getId() ?>">
                                    <i class="ri-delete-bin-line"></i> Eliminar
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
                    <table class="table table-sm table-bordered mb-0 no-">
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
modalConfirmacion();
require_once "views/templates/components/modal_flash.php";
modalFlash();
footerAdmin();
?>