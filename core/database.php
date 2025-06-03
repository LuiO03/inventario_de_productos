<?php

    
    class Database {

        private $host;
        private $db;
        private $user;
        private $password;
        private $charset;

        public function __construct(){
            $this->host = DB_HOST;
            $this->db = DB_NAME;
            $this->user = DB_USER;
            $this->password = DB_PASS;
            $this->charset = DB_CHARSET;
        }

        function connect(){
            try {
                $connection  = 'mysql:host=' . $this->host . ';dbname=' . $this->db . ';charset=' . $this->charset;
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                $pdo = new PDO($connection, $this->user, $this->password, $options);
                return $pdo;
            } catch (PDOException $e) {
                echo "Error de conexión: " . $e->getMessage();
                return null;
            }
        }
    }
?>