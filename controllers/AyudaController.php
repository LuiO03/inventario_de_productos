<?php 
    class AyudaController extends Controller{
        function __construct(){
            parent::__construct();
            $this->view->traerVista('ayuda/index');
        }
    }
?>