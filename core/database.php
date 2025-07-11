<?php

    
    class Database {

        // Patrón Singleton para asegurar una única instancia de la conexión
        private static $instance = null;
        private $pdo;

        // base de datos parametros
        private $host;
        private $db;
        private $user;
        private $password;
        private $charset;

        public function __construct(){
            // Asigna las constantes dentro del constructor
            $this->host = DB_HOST;
            $this->db = DB_NAME;
            $this->user = DB_USER;
            $this->password = DB_PASS;
            $this->charset = DB_CHARSET;
            $connection = "mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                $this->pdo = new PDO($connection, $this->user, $this->password, $options);
                $this->pdo->exec("SET time_zone = '-05:00'"); // Zona horaria
            } catch (PDOException $e) {
                error_log("Error de conexión DB: " . $e->getMessage());
                $this->pdo = null;
            }
        }

        // Método público para acceder a la única instancia
        public static function getInstance() {
            if (self::$instance === null) {
                self::$instance = new Database();
            }
            return self::$instance;
        }

        // Método para obtener la conexión PDO
        public function getConnection() {
            return $this->pdo;
        }
    }
?>
