<?php
    function base_url(){
		return BASE_URL;
	}
    function media()
    {
        return BASE_URL."/public";
    }
    function headerAdmin(){
        $view_header = "views/templates/layouts/header.php";
        require_once ($view_header);
    }
    function footerAdmin(){
        $view_footer = "views/templates/layouts/footer.php";
        require_once ($view_footer);
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