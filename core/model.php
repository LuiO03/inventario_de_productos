<?php

    require_once 'core/database.php';

    class Model{
		/** @var PDO */
        protected $PDO;
        function __construct(){
			//$conn = new Database();
            //$this->PDO = $conn->connect();
            $this->PDO = Database::getInstance()->getConnection();// Obtener la conexión a la base de datos
			// Validación útil en caso de error
			if ($this->PDO === null) {
				error_log("Conexión PDO es null en Model.");
			}
        }
        
    }
?>