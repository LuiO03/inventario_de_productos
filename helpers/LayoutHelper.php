<?php
    // Retorna la URL base del proyecto
    function base_url() {
        return BASE_URL;
    }

    // Ruta hacia la carpeta pública
    function media() {
        return BASE_URL . "/public";
    }

    // Carga el header del panel administrador
    function headerAdmin() {
        require_once "views/templates/layouts/header.php";
    }

    // Carga el footer del panel administrador
    function footerAdmin() {
        require_once "views/templates/layouts/footer.php";
    }

    function modalFlash($mensaje) {
        if (!empty($mensaje)) {
            include "views/templates/components/modal_mensaje.php";
        }
    }

    function modalConfirmacion() {
        require_once "views/templates/components/modal_confirmacion.php";
    }
    
    function menuFlotante() {
        require_once "views/templates/components/menuflotante.php";
    }

    function alertValidate() {
        require_once "views/templates/components/alert-validate.php";
    }

        function getEntidadDinamica(): array {
        $controlador = strtolower(Router::$controller ?? 'main');
        $metodo = Router::$method ?? 'index';

        $titulo = ucfirst($controlador);
        $icono = match($controlador) {
            'producto' => 'ri-t-shirt-fill',
            'cliente'  => 'ri-user-fill',
            'categoria'  => 'ri-price-tag-3-fill',
            'usuario'  => 'ri-shield-user-fill',
            'servicio' => 'ri-store-fill',
            default    => 'ri-folder-fill'
        };

        return [
            'controlador' => $controlador,
            'metodo'      => $metodo,
            'titulo'      => $titulo,
            'icono'       => $icono
        ];
    }
    function partialBreadcrumb() {
        require_once "views/templates/components/breadcrumb.php";
    }

    $GLOBALS['pageTitle'] = APP_NAME;
    function pageTitle($default = null) {
        return $GLOBALS['pageTitle'] ?? ($default ?? APP_NAME);
    }
    function getModal(string $nameModal, $data)
    {
        $view_modal = "Views/templates/modals/{$nameModal}.php";
        require_once $view_modal;        
    }
?>