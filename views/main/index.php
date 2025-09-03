<?php headerAdmin(); ?>

<div class="contenedor-header-home">
    <img src="<?= media() ?>/images/usuarios/pikachu.jpg" alt="Avatar" class="header-user-avatar">
    <div class="header-user-info">
        <h2 class="text-primario">Buen día, Luis Q. Osorio</h2>
        <div class="">
            <p>Eres <strong>Administrador(a)</strong></p>
            <small>Puedes gestionar todos los módulos del sistema.</small>
        </div>
    </div>
</div>
<div class="contenedor-footer-home">
    <div class="wrapper_tabs">
        <input type="radio" class="radio_tab" name="tabs" id="tab1" checked>
        <input type="radio" class="radio_tab" name="tabs" id="tab2">

        <div class="tabs">
            <label for="tab1" class="label_tab">Inicio</label>
            <label for="tab2" class="label_tab">Colaboradores</label>
        </div>
        <div id="tab-inicio" class="tab-content">
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
                                        <p>Productos</p>
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
                                        <p>Categorías</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Marcas -->
                    <div class="col-4 col-md-4 col-lg-3">
                        <div class="card targeta h-100 border-0">
                            <a href="<?= BASE_URL ?>marca" class="h-100">
                                <div class="targeta-conteiner">
                                    <div class="card-icon-bg primary">
                                        <i class="ri-award-line display-4 text-primary"></i>
                                    </div>
                                    <div class="card-info">
                                        <h1><?= $totalMarcas ?? 0 ?></h1>
                                        <p>Marcas</p>
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
                                        <p>Clientes</p>
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
                                        <p>Usuarios</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Roles -->
                    <div class="col-4 col-md-4 col-lg-3">
                        <div class="card targeta h-100 border-0">
                            <a href="<?= BASE_URL ?>rol" class="h-100">
                                <div class="targeta-conteiner">
                                    <div class="card-icon-bg secondary">
                                        <i class="ri-shield-user-line display-4 text-secondary"></i>
                                    </div>
                                    <div class="card-info">
                                        <h1><?= $totalRols ?? 0 ?></h1>
                                        <p>Roles</p>
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
                                        <p>Pedidos</p>
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
                                        <p>Servicios</p>
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
                                        <p>Blog</p>
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
                                        <p>Mensajes</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="tab-colaboradores" class="tab-content">
            <div class="contenedor-menu">
                <div class="row gx-2 gy-2 gy-md-4 gx-md-4">
                    <?php if (!empty($colaboradores)): ?>
                        <?php foreach ($colaboradores as $colaborador): ?>
                            <div class="col-6 col-md-3 col-lg-2">
                                <div class="card targeta h-100 border-0">
                                    <div class="targeta-conteiner-user">
                                        <img src="<?= !empty($colaborador->getImagen()) 
                                                        ? media() . "/images/usuarios/" . $colaborador->getImagen() 
                                                        : media() . "/images/usuarios/default.jpg" ?>" 
                                            class="avatar-user" 
                                            alt="<?= htmlspecialchars($colaborador->getNombre()) ?>">
                                        <div class="card-info-user">
                                            <p class="fw-bolder"><?= htmlspecialchars($colaborador->getNombre()) ?></p>
                                            <p>
                                                <?= htmlspecialchars($colaborador->getApellido()) ?>
                                            </p>
                                            <small><?= htmlspecialchars($colaborador->nombreRol ?? "Sin rol") ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">No hay colaboradores registrados.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    modalFlash($mensaje);
    footerAdmin();
?>