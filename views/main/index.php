<?php headerAdmin(); ?>

<div class="contenedor-header-home">
    <img src="<?= media() ?>/images/usuarios/pikachu.jpg" alt="Avatar" class="header-user-avatar">
    <div class="header-user-info">
        <h2 class="text-primario">Buen día, Luis Osorio</h2>
        <div>
            <small>
                Eres <strong>Administrador</strong>
            </small>
            <p>
                Puedes gestionar todos los módulos del sistema.
            </p>
        </div>
    </div>
</div>
<div class="contenedor-footer-home">
    <div class="tabs-container">
        <div class="tabs-inner">
            <button class="tab-button active" data-tab="inicio">Inicio</button>
            <button class="tab-button" data-tab="colaboradores">Colaboradores</button>
            <div class="tab-indicator"></div>
        </div>
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
    
                <!-- Colaborador 1 -->
                <div class="col-6 col-md-2">
                    <div class="card targeta h-100 border-0">
                        <div class="targeta-conteiner-user">
                            <img src="https://i.pravatar.cc/100?img=1" class="avatar-user" alt="Usuario 1">
                            <div class="card-info-user">
                                <h3>Lucía Ramírez</h3>
                                <small>Administradora</small>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Colaborador 2 -->
                <div class="col-6 col-md-2">
                    <div class="card targeta h-100 border-0">
                        <div class="targeta-conteiner-user">
                            <img src="https://i.pravatar.cc/100?img=2" class="avatar-user" alt="Usuario 2">
                            <div class="card-info-user">
                                <h3>Carlos Gómez</h3>
                                <small>Editor</small>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Colaborador 3 -->
                <div class="col-6 col-md-2">
                    <div class="card targeta h-100 border-0">
                        <div class="targeta-conteiner-user">
                            <img src="https://i.pravatar.cc/100?img=3" class="avatar-user" alt="Usuario 3">
                            <div class="card-info-user">
                                <h3>Andrea Soto</h3>
                                <small>Diseñadora</small>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Colaborador 4 -->
                <div class="col-6 col-md-2">
                    <div class="card targeta h-100 border-0">
                        <div class="targeta-conteiner-user">
                            <img src="https://i.pravatar.cc/100?img=4" class="avatar-user" alt="Usuario 4">
                            <div class="card-info-user">
                                <h3>Pedro Luján</h3>
                                <small>Soporte Técnico</small>
                            </div>
                        </div>
                    </div>
                </div>
    
            </div>
        </div>
    </div>
</div>

<script>
  const tabButtons = document.querySelectorAll('.tab-button');
  const tabContents = document.querySelectorAll('.tab-content');
  const tabIndicator = document.querySelector('.tab-indicator');
  const tabs = Array.from(tabButtons);
  const totalTabs = tabs.length;
  let currentTab = 0;
  let startX = 0;

  function activateTab(index) {
    const selectedBtn = tabs[index];
    currentTab = index;

    // Activar botón y contenido
    tabButtons.forEach(btn => btn.classList.remove('active'));
    selectedBtn.classList.add('active');

    tabContents.forEach(content => {
      content.classList.toggle('active', content.id === `tab-${selectedBtn.dataset.tab}`);
    });

    // Actualizar indicador
    const rect = selectedBtn.getBoundingClientRect();
    const parentRect = selectedBtn.parentElement.getBoundingClientRect();
    tabIndicator.style.left = `${rect.left - parentRect.left}px`;
    tabIndicator.style.width = `${rect.width}px`;
  }

  // Eventos click en tabs
  tabButtons.forEach((btn, index) => {
    btn.addEventListener('click', () => activateTab(index));
  });

  // Al cargar
  window.addEventListener("DOMContentLoaded", () => {
    const activeIndex = tabs.findIndex(btn => btn.classList.contains("active"));
    activateTab(activeIndex >= 0 ? activeIndex : 0);
  });
</script>

<?php
    modalFlash($mensaje);
    footerAdmin();
?>