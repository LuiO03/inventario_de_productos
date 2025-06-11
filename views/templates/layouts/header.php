

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $title ??  APP_NAME .'- Panel de Administración' ?>
    </title>
    <meta name="keywords" content="<?= APP_KEYWORDS ?>">
    <meta name="description" content="<?= APP_DESCRIPTION ?>">
    <meta name="author" content="<?= APP_AUTHOR ?>">
    
    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="<?= APP_LOGO ?>" type="image/x-icon">
    <!--=============== REMIXICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
    <!--=============== BOOTSTRAP ===============-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/base.css">
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
                <a href="#" class="sidebar-link">
                    <i class="ri-logout-box-r-line"></i>
                    <span class="text-capitalize">modo oscuro</span>
                </a>
            </div>
        </aside>
        <header class="header">
            <nav class="nav_admin">
                <div class="boton-superiores">
                    <a class="ver_sitio" href="../../index.php" class="sidebar-link" target="_blank">
                        <i class="fa-solid fa-eye"></i>
                        <span>Cerrar sesión</span>
                    </a>
                </div>
                <span class="nav_fecha">
                    <?= fechaC(); ?>
                </span>
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
            </nav>
            <main class="">