<?php

    class ProductoController extends Controller {

        public function __construct() {
            parent::__construct();
            $this->view->render('producto/index');
        }

    }
?>