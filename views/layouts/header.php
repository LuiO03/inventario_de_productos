<?php 
    require_once 'config/config.php';
    require_once 'helpers/fecha.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barra Lateral con Bootstrap</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />

    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/base.css">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="logo-content">
                <button class="toggle-btn" type="button">
                    <i class="ri-menu-line"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#">CodzSword</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="ayudacontroller" class="sidebar-link" title="Perfil">
                        <i class="ri-user-line"></i>
                        <span>Perfil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" title="Tareas">
                        <i class="ri-task-line"></i>
                        <span>Tareas</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#auth" aria-expanded="false" aria-controls="auth" title="Autenticación">
                        <i class="ri-shield-user-line"></i>
                        <span>Autenticación</span>
                    </a>
                    <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-subitem">
                            <i class="ri-login-box-line"></i>
                            <span href="#" class="sidebarsub-link">Iniciar sesión</span>
                        </li>
                        <li class="sidebar-subitem">
                            <i class="ri-user-add-line"></i>
                            <span href="#" class="sidebarsub-link">Registrarse</span>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" title="Notificaciones">
                        <i class="ri-notification-3-line"></i>
                        <span>Notificaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" title="Configuración">
                        <i class="ri-settings-3-line"></i>
                        <span>Configuración</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" title="Dashboard">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" title="Usuarios">
                        <i class="ri-group-line"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" title="Reportes">
                        <i class="ri-bar-chart-line"></i>
                        <span>Reportes</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" title="Mensajes">
                        <i class="ri-mail-line"></i>
                        <span>Mensajes</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" title="Ayuda">
                        <i class="ri-question-line"></i>
                        <span>Ayuda</span>
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
            <main class="text-center">