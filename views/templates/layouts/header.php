

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
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/base.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/sidebar.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/datatable.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/tabla.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/breadcrumb.css">
    
      <script>
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
    <div class="wrapper">
        <aside id="sidebar">
            <div class="logo-content">
                <button class="toggle-btn" type="button">
                    <img src="<?= BASE_URL ?>public/images/logos/logo.png" alt="">
                </button>
                <div class="sidebar-logo">
                    <a href="<?= BASE_URL ?>">Geckomerce</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="<?= BASE_URL ?>producto" class="sidebar-link" title="Productos">
                        <i class="ri-box-3-line"></i>
                        <span>Productos</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="ayudacontroller" class="sidebar-link" title="Perfil">
                        <i class="ri-user-line"></i>
                        <span>Perfil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" title="Categorías">
                        <i class="ri-price-tag-3-line"></i>
                        <span>Categorías</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" title="Clientes">
                        <i class="ri-user-3-line"></i>
                        <span>Clientes</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" title="Ventas">
                        <i class="ri-shopping-cart-line"></i>
                        <span>Ventas</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" title="Compras">
                        <i class="ri-shopping-bag-line"></i>
                        <span>Compras</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" title="Proveedores">
                        <i class="ri-truck-line"></i>
                        <span>Proveedores</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" title="Reportes">
                        <i class="ri-bar-chart-line"></i>
                        <span>Reportes</span>
                    </a>
                </li>

            </ul>
            <div class="sidebar-footer">
                <button id="toggleModeTheme" class="sidebar-tema text-xs">
                    <i class="ri-contrast-2-line"></i>
                    <span class="text-capitalize">modo oscuro</span>
                </button>
            </div>
        </aside>
        <div class="content">
            <nav class="nav-header">
                <div class="nav_usuario">
                    <img src="<?= BASE_URL ?>public/images/pikachu.jpg" alt="foto de perfil">
                    <div class="usuario_datos">
                        <span class="usuario_nombre text-capitalize">
                            Luis alberto
                        </span>
                        <span class="usuario_rol text-capitalize">
                            administrador
                        </span>
                    </div>
                </div>
                <span class="nav-fecha text-md">
                    <?= fechaC(); ?>
                </span>
                
                <div class="botones_header">
                    <a class="cerrar-sesión" href="../../index.php" target="_blank">
                        <i class="ri-shut-down-fill"></i>
                        <span>Cerrar sesión</span>
                    </a>
                </div>
            </nav>
            <main class="main-content">