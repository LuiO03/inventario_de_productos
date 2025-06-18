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
            include "views/templates/components/modal_flash.php";
        }
    }

    function modalConfirmacion() {
        require_once "views/templates/components/modal_confirmacion.php";
    }


    $GLOBALS['pageTitle'] = APP_NAME;
    function pageTitle($default = null) {
        return $GLOBALS['pageTitle'] ?? ($default ?? APP_NAME);
    }

    // Limpieza básica de texto para prevenir XSS y algunas inyecciones
    function strClean($strCadena) {
        $string = trim(preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $strCadena));
        $string = stripslashes($string);
        $string = strip_tags($string); // elimina HTML/PHP tags

        // Elimina patrones comunes peligrosos (mejor prevenir que lamentar)
        $peligros = [
            "/<script\b[^>]*>(.*?)<\/script>/is",
            "/(SELECT|DELETE|INSERT|DROP|COUNT|LIKE)\s+/i",
            "/OR\s+['\"´]?[a-z0-9]+['\"´]?\s*=\s*['\"´]?[a-z0-9]+['\"´]?/i",
            "/--/", "/\^/", "/\[/", "/\]/", "/==/"
        ];
        return preg_replace($peligros, "", $string);
    }

    // Elimina tildes y caracteres especiales
    function clear_cadena(string $cadena) {
        $cadena = str_replace(
            ['Á','À','Â','Ä','á','à','ä','â','ª','É','È','Ê','Ë','é','è','ë','ê',
            'Í','Ì','Ï','Î','í','ì','ï','î','Ó','Ò','Ö','Ô','ó','ò','ö','ô',
            'Ú','Ù','Û','Ü','ú','ù','ü','û','Ñ','ñ','Ç','ç',',','.',';',':'],
            ['A','A','A','A','a','a','a','a','a','E','E','E','E','e','e','e','e',
            'I','I','I','I','i','i','i','i','O','O','O','O','o','o','o','o',
            'U','U','U','U','u','u','u','u','N','n','C','c','','','',''],
            $cadena
        );
        return $cadena;
    }

    // Valida un email
    function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Escapa salida para evitar XSS (en vistas, por ejemplo)
    function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    function getModal(string $nameModal, $data)
    {
        $view_modal = "Views/templates/modals/{$nameModal}.php";
        require_once $view_modal;        
    }
    function getFile(string $url, $data)
    {
        ob_start();
        require_once("Views/{$url}.php");
        $file = ob_get_clean();
        return $file;        
    }
?>