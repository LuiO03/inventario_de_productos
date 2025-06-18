<?php headerAdmin(); ?>

<style>
    .bg-danger-dark { background-color: #a30000; }
    .bg-success-dark { background-color: #00786D; }
    .bg-primary-dark { background-color: #003c8f; }
    .bg-secondary-dark { background-color: #5a6268; }
    .bg-warning-dark { background-color: #b37f00; }
    .bg-info-dark { background-color: #017e9f; }
</style>

<h1 class="text-center m-4">Panel Administrativo</h1>
<div class="contenedor">

    <div class="row g-4">
        <!-- Categorías -->
        <div class="col-md-6 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>/categorias" class="text-decoration-none h-100">
                    <div class="d-flex p-0 align-items-stretch h-100">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-success-dark" style="width: 33%;">
                            <i class="ri-folders-line display-4 text-white"></i>
                        </div>
                        <div class="flex-grow-1 bg-success d-flex flex-column justify-content-between px-3 py-3">
                            <div>
                                <h6 class="card-title text-white mb-1">Categorías</h6>
                            </div>
                            <div class="text-end mt-auto">
                                <small class="text-white-50">Total: <?= $totalCategorias ?? 0 ?></small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Productos -->
        <div class="col-md-6 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>/producto" class="text-decoration-none h-100">
                    <div class="d-flex p-0 align-items-stretch h-100">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-danger-dark" style="width: 33%;">
                            <i class="ri-shirt-line display-4 text-danger"></i>
                        </div>
                        <div class="flex-grow-1 bg-danger d-flex flex-column justify-content-between px-3 py-3">
                            <div>
                                <h6 class="card-title text-white mb-1">Productos</h6>
                            </div>
                            <div class="text-end mt-auto">
                                <small class="text-white-50">Total: <?= $totalProductos ?? 0 ?></small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Clientes -->
        <div class="col-md-6 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>/clientes" class="text-decoration-none h-100">
                    <div class="d-flex p-0 align-items-stretch h-100">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-primary-dark" style="width: 33%;">
                            <i class="ri-user-3-line display-4 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 bg-primary d-flex flex-column justify-content-between px-3 py-3">
                            <div>
                                <h6 class="card-title text-white mb-1">Clientes</h6>
                            </div>
                            <div class="text-end mt-auto">
                                <small class="text-white-50">Total: <?= $totalClientes ?? 0 ?></small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Usuarios -->
        <div class="col-md-6 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>/usuarios" class="text-decoration-none h-100">
                    <div class="d-flex p-0 align-items-stretch h-100">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-secondary-dark" style="width: 33%;">
                            <i class="ri-admin-line display-4 text-secondary"></i>
                        </div>
                        <div class="flex-grow-1 bg-secondary d-flex flex-column justify-content-between px-3 py-3">
                            <div>
                                <h6 class="card-title text-white mb-1">Usuarios</h6>
                            </div>
                            <div class="text-end mt-auto">
                                <small class="text-white-50">Total: <?= $totalUsuarios ?? 0 ?></small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Pedidos -->
        <div class="col-md-6 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>/pedidos" class="text-decoration-none h-100">
                    <div class="d-flex p-0 align-items-stretch h-100">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-warning-dark" style="width: 33%;">
                            <i class="ri-shopping-cart-2-line display-4 text-warning"></i>
                        </div>
                        <div class="flex-grow-1 bg-warning d-flex flex-column justify-content-between px-3 py-3">
                            <div>
                                <h6 class="card-title text-dark mb-1">Pedidos</h6>
                            </div>
                            <div class="text-end mt-auto">
                                <small class="text-muted">Total: <?= $totalPedidos ?? 0 ?></small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Servicios -->
        <div class="col-md-6 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>/servicios" class="text-decoration-none h-100">
                    <div class="d-flex p-0 align-items-stretch h-100">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-info-dark" style="width: 33%;">
                            <i class="ri-tools-line display-4 text-info"></i>
                        </div>
                        <div class="flex-grow-1 bg-info d-flex flex-column justify-content-between px-3 py-3">
                            <div>
                                <h6 class="card-title text-white mb-1">Servicios</h6>
                            </div>
                            <div class="text-end mt-auto">
                                <small class="text-white-50">Total: <?= $totalServicios ?? 0 ?></small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Blog -->
        <div class="col-md-6 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>/blog" class="text-decoration-none h-100">
                    <div class="d-flex p-0 align-items-stretch h-100">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-dark" style="width: 33%;">
                            <i class="ri-article-line display-4 text-white"></i>
                        </div>
                        <div class="flex-grow-1 bg-dark d-flex flex-column justify-content-between px-3 py-3">
                            <div>
                                <h6 class="card-title text-white mb-1">Blog</h6>
                            </div>
                            <div class="text-end mt-auto">
                                <small class="text-white-50">Total: <?= $totalBlog ?? 0 ?></small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Mensajes -->
        <div class="col-md-6 col-lg-3 border-0">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>/mensajes" class="text-decoration-none h-100">
                    <div class="d-flex p-0 align-items-stretch h-100">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-dark" style="width: 33%;">
                            <i class="ri-mail-line display-4 text-white"></i>
                        </div>
                        <div class="flex-grow-1 bg-dark d-flex flex-column justify-content-between px-3 py-3">
                            <div>
                                <h6 class="card-title text-white mb-1">Mensajes</h6>
                            </div>
                            <div class="text-end mt-auto">
                                <small class="text-white-50">Total: <?= $totalMensajes ?? 0 ?></small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
    modalConfirmacion();
    modalFlash($mensaje);
    footerAdmin();
?>
