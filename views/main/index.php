<?php headerAdmin(); ?>

<div class="contenedor-header">
    <h1>Panel Administrativo</h1>
    <p>
        Desde esta sección puedes gestionar todos los productos registrados.
    </p>
</div>

<div class="contenedor-menu">
    <div class="row gx-2 gy-2 gy-md-4 gx-md-4">
        <!-- Productos -->
        <div class="col-4 col-md-4 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>producto" class="h-100">
                    <div class="targeta-conteiner">
                        <div class="card-icon-bg danger">
                            <i class="ri-shirt-line display-4 text-danger"></i>
                        </div>
                        <div class="card-info">
                            <h1><?= $totalProductos ?? 0 ?></h1>
                            <small>Productos</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Categorías -->
        <div class="col-4 col-md-4 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>categoria" class="h-100">
                    <div class="targeta-conteiner">
                        <div class="card-icon-bg info">
                            <i class="ri-price-tag-3-line display-4 text-info"></i>
                        </div>
                        <div class="card-info">
                            <h1><?= $totalCategorias ?? 0 ?></h1>
                            <small>Categorías</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Clientes -->
        <div class="col-4 col-md-4 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>cliente" class="h-100">
                    <div class="targeta-conteiner">
                        <div class="card-icon-bg success">
                            <i class="ri-user-line display-4 text-success"></i>
                        </div>
                        <div class="card-info">
                            <h1><?= $totalClientes ?? 0 ?></h1>
                            <small>Clientes</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Usuarios -->
        <div class="col-4 col-md-4 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>usuario" class="h-100">
                    <div class="targeta-conteiner">
                        <div class="card-icon-bg primary">
                            <i class="ri-admin-line display-4 text-primary"></i>
                        </div>
                        <div class="card-info">
                            <h1><?= $totalUsuarios ?? 0 ?></h1>
                            <small>Usuarios</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Pedidos -->
        <div class="col-4 col-md-4 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>pedido" class="h-100">
                    <div class="targeta-conteiner">
                        <div class="card-icon-bg warning">
                            <i class="ri-shopping-cart-2-line display-4 text-warning"></i>
                        </div>
                        <div class="card-info">
                            <h1><?= $totalPedidos ?? 0 ?></h1>
                            <small>Pedidos</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Servicios -->
        <div class="col-4 col-md-4 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>servicio" class="h-100">
                    <div class="targeta-conteiner">
                        <div class="card-icon-bg info">
                            <i class="ri-tools-line display-4 text-info"></i>
                        </div>
                        <div class="card-info">
                            <h1><?= $totalServicios ?? 0 ?></h1>
                            <small>Servicios</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Blog -->
        <div class="col-4 col-md-4 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>blog" class="h-100">
                    <div class="targeta-conteiner">
                        <div class="card-icon-bg secondary">
                            <i class="ri-article-line display-4 text-secondary"></i>
                        </div>
                        <div class="card-info">
                            <h1><?= $totalBlog ?? 0 ?></h1>
                            <small>Blog</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Mensajes -->
        <div class="col-4 col-md-4 col-lg-3">
            <div class="card targeta h-100 border-0">
                <a href="<?= BASE_URL ?>mensaje" class="h-100">
                    <div class="targeta-conteiner">
                        <div class="card-icon-bg primary">
                            <i class="ri-mail-line display-4 text-primary"></i>
                        </div>
                        <div class="card-info">
                            <h1><?= $totalMensajes ?? 0 ?></h1>
                            <small>Mensajes</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>

<?php
modalFlash($mensaje);
footerAdmin();
?>
