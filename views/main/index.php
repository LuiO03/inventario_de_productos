<?php headerAdmin(); ?>

<div class="contenedor-header">
    <h1 class="text-center">Panel Administrativo</h1>
    <p class="text-center">
        Desde esta sección puedes gestionar todos los productos registrados.
    </p>
</div>
<div class="">
    <div class="row g-4">
        <!-- Productos -->
        <div class="col-sm-6 col-md-6 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>producto" class="h-100">
                    <div class="d-flex p-2 align-items-stretch h-100">
                        <div class="card-icon-bg danger">
                            <i class="ri-shirt-line display-4 text-danger"></i>
                        </div>
                        <div class="flex-grow-1 d-flex flex-column justify-content-between px-3 py-3">
                            <div class="text-start mt-auto">
                                <h1><?= $totalProductos ?? 0 ?></h1>
                            </div>
                            <div>
                                <small class="card-title">Productos</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Clientes -->
        <div class="col-md-6 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>cliente" class="h-100">
                    <div class="d-flex p-2 align-items-stretch h-100">
                        <div class="card-icon-bg success">
                            <i class="ri-user-line display-4 text-success"></i>
                        </div>
                        <div class="flex-grow-1 d-flex flex-column justify-content-between px-3 py-3">
                            <div class="text-start mt-auto">
                                <h1><?= $totalClientes ?? 0 ?></h1>
                            </div>
                            <div>
                                <small class="card-title">Clientes</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Usuarios -->
        <div class="col-md-6 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>usuario" class="h-100">
                    <div class="d-flex p-2 align-items-stretch h-100">
                        <div class="card-icon-bg primary">
                            <i class="ri-admin-line display-4 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 d-flex flex-column justify-content-between px-3 py-3">
                            <div class="text-start mt-auto">
                                <h1><?= $totalUsuarios ?? 0 ?></h1>
                            </div>
                            <div>
                                <small class="card-title">Usuarios</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Pedidos -->
        <div class="col-md-6 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>pedido" class="h-100">
                    <div class="d-flex p-2 align-items-stretch h-100">
                        <div class="card-icon-bg warning">
                            <i class="ri-shopping-cart-2-line display-4 text-warning"></i>
                        </div>
                        <div class="flex-grow-1 d-flex flex-column justify-content-between px-3 py-3">
                            <div class="text-start mt-auto">
                                <h1><?= $totalPedidos ?? 0 ?></h1>
                            </div>
                            <div>
                                <small class="card-title">Pedidos</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Servicios -->
        <div class="col-md-6 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>servicio" class="h-100">
                    <div class="d-flex p-2 align-items-stretch h-100">
                        <div class="card-icon-bg info">
                            <i class="ri-tools-line display-4 text-info"></i>
                        </div>
                        <div class="flex-grow-1 d-flex flex-column justify-content-between px-3 py-3">
                            <div class="text-start mt-auto">
                                <h1><?= $totalServicios ?? 0 ?></h1>
                            </div>
                            <div>
                                <small class="card-title">Servicios</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Blog -->
        <div class="col-md-6 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>blog" class="h-100">
                    <div class="d-flex p-2 align-items-stretch h-100">
                        <div class="card-icon-bg secondary">
                            <i class="ri-article-line display-4 text-secondary"></i>
                        </div>
                        <div class="flex-grow-1 d-flex flex-column justify-content-between px-3 py-3">
                            <div class="text-start mt-auto">
                                <h1><?= $totalBlog ?? 0 ?></h1>
                            </div>
                            <div>
                                <small class="card-title">Blog</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Mensajes -->
        <div class="col-md-6 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>mensaje" class="h-100">
                    <div class="d-flex p-2 align-items-stretch h-100">
                        <div class="card-icon-bg primary">
                            <i class="ri-mail-line display-4 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 d-flex flex-column justify-content-between px-3 py-3">
                            <div class="text-start mt-auto">
                                <h1><?= $totalMensajes ?? 0 ?></h1>
                            </div>
                            <div>
                                <small class="card-title">Mensajes</small>
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
