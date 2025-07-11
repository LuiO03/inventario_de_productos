<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= pageTitle() ?></title>

  <meta name="keywords" content="<?= APP_KEYWORDS ?>">
  <meta name="description" content="<?= APP_DESCRIPTION ?>">
  <meta name="author" content="<?= APP_AUTHOR ?>">

  <!--=============== FAVICON ===============-->
  <link rel="shortcut icon" href="<?= APP_LOGO ?>" type="image/x-icon">
  <!--=============== REMIXICONS ===============-->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
  <!--=============== BOOTSTRAP ===============-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- DataTables Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
  <!-- DataTables Responsive Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

  <!--=============== CSS ===============-->
  <link rel="stylesheet" href="<?=base_url()?>public/css/base.css">
  <link rel="stylesheet" href="<?=base_url()?>public/css/datatable.css">
  <link rel="stylesheet" href="<?=base_url()?>public/css/tabla.css">
  <link rel="stylesheet" href="<?=base_url()?>public/css/breadcrumb.css">
  <link rel="stylesheet" href="<?=base_url()?>public/css/menuflotante.css">
  <link rel="stylesheet" href="<?=base_url()?>public/css/menu.css">
  <link rel="stylesheet" href="<?=base_url()?>public/css/dashboard.css">
  <link rel="stylesheet" href="<?=base_url()?>public/css/formularios.css">
  <link rel="stylesheet" href="<?=base_url()?>public/css/alert-validate.css">
  <script>
    if (localStorage.getItem("sidebar-estado") === "close") {
      document.documentElement.classList.add("sidebar-cerrado");
    }
    (function() {
      try {
        const savedTheme = localStorage.getItem('theme');
        const prefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
        const theme = savedTheme || (prefersLight ? 'light' : 'dark');

        if (theme === 'light') {
          document.documentElement.classList.add('light-mode');
        }
      } catch (e) {
        console.error('Error al aplicar el tema desde el inicio', e);
      }
    })();
  </script>
</head>

<body>
  <!-- Sidebar principal -->
  <nav id="sidebar">
    <div class="sidebar-header">
      <img class="sidebar-logo" src="<?=base_url()?>public/images/logos/logo.png" alt="logo del proyecto" />
      <p class="sidebar-logo-texto"><strong>Gecko</strong><span>merce</span></p>
    </div>
    <ul class="sidebar-menu">
      <li class="active">
        <a href="<?=base_url()?>" data-label="Inicio">
          <i class="ri-home-4-line icon"></i><span>Home</span>
        </a>
      </li>
      <li>
        <button
          class="dropdown-btn"
          onclick="toggleSubMenu(this)"
          data-label="Tienda" data-id="create">
          <i class="ri-store-2-line icon"></i><span>Tienda</span>
          <i class="ri-arrow-down-s-line arrow-icon"></i>
        </button>
        <ul class="sub-menu">
          <div>
            <li>
              <a href="<?=base_url()?>producto" data-label="Producto">
                <i class="ri-box-3-line icon"></i><span>Producto</span>
              </a>
            </li>
            <li>
              <a href="<?=base_url()?>categoria" data-label="Categorías">
                <i class="ri-price-tag-3-line icon"></i><span>Categorías</span>
              </a>
            </li>
            <li>
              <a href="#" data-label="Carga Masiva">
                <i class="ri-upload-cloud-line icon"></i><span>Carga Masiva</span>
              </a>
            </li>
          </div>
        </ul>
      </li>
      <li>
        <a href="#" data-label="Usuarios">
          <i class="ri-id-card-line icon"></i><span>Usuarios</span>
        </a>
      </li>
      <li>
        <a href="#" data-label="Clientes">
          <i class="ri-account-box-line icon"></i><span>Clientes</span>
        </a>
      </li>
      <li>
        <a href="#" data-label="Ventas">
          <i class="ri-shopping-cart-line icon"></i><span>Ventas</span>
        </a>
      </li>
      <li>
        <a href="#" data-label="Ventas">
          <i class="ri-shopping-cart-line icon"></i><span>Ventas</span>
        </a>
      </li>
      <li>
        <a href="#" data-label="Ventas">
          <i class="ri-shopping-cart-line icon"></i><span>Ventas</span>
        </a>
      </li>
      <li>
        <a href="#" data-label="Ventas">
          <i class="ri-shopping-cart-line icon"></i><span>Ventas</span>
        </a>
      </li>
    </ul>
    <div class="sidebar-footer">
      <div data-label="Prender Luces" class="sidebar-footer-content">
        <i id="modoIcono" class="ri-lightbulb-line icon icon-animado"></i>
        <span id="modoTexto" class="sidebar-footer-texto texto-animado">Prender Luces</span>
        <label class="dark-toggle-switch">
          <input type="checkbox" id="darkToggle" />
          <span class="dark-toggle-slider"></span>
        </label>
      </div>
    </div>
  </nav>
  <!-- Contenedor principal -->
  <div class="layout-wrapper">
    <!-- Topbar -->
    <header class="topbar">
      <div class="topbar-left">
        <button id="toggle-btn">
          <i id="toggle-icon" class="ri-arrow-left-double-fill"></i>
        </button>
      </div>
      <div class="topbar-center">
        <span class="current-date"><?= FechaHelper::fechaActual(); ?></span>
        <div class="logo-central">
          <p class="central-logo-texto"><strong>Gecko</strong><span>merce</span></p>
        </div>
      </div>
      <div class="topbar-right">
        <img src="<?=media()?>/images/usuarios/pikachu.jpg" alt="Avatar" class="user-avatar" />
        <button class="icon-button">
          <i class="ri-expand-up-down-line"></i>
        </button>
      </div>
    </header>

    <!-- Sidebar del usuario -->
    <aside class="user-sidebar" id="userSidebar">
      <div class="user-sidebar-header">
        <img src="<?=media()?>/images/usuarios/pikachu.jpg" alt="Avatar" class="user-avatar-large" />

        <div class="user-info-wrapper">
          <div class="user-text">
            <h6 class="user-name"><?= $_SESSION['nombre_usuario'] ?></h6>
            <p class="user-role">Administrador</p>
          </div>
          <button class="edit-user-btn" title="Editar perfil">
            <i class="ri-pencil-fill"></i>
          </button>
        </div>
      </div>

      <ul class="user-sidebar-menu">
        <li><i class="ri-user-line icon"></i><span>Ver perfil</span></li>
        <li>
          <i class="ri-settings-3-line icon"></i><span>Configuración</span>
        </li>
        <li>
          <i class="ri-shut-down-line icon"></i></i><span>Cerrar sesión</span>
        </li>
      </ul>
      <div class="user-sidebar-section">
        <h4>Notificaciones</h4>
        <ul class="notifications-list icon">
          <li>
            <i class="ri-notification-3-line"></i> Tienes 3 tareas pendientes
          </li>
          <li><i class="ri-mail-line"></i> Nuevo mensaje de soporte</li>
          <li>
            <i class="ri-calendar-event-line"></i> Reunión hoy a las 3 PM
          </li>
        </ul>
      </div>
    </aside>

    <!-- Contenido principal -->
    <main>