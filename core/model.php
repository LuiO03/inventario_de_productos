<?php

    require_once 'core/database.php';

    class Model{

        protected $PDO;
        private $strquery;
        private $arrValues;
        function __construct(){
            //$this->db = Database::getInstance()->getConnection();// Obtener la conexión a la base de datos
            $conn = new Database();
            $this->PDO = $conn->connect();
        }
        /*
        
        public function insert(string $query, array $arrValues)
		{
			$this->strquery = $query;
			$this->arrValues = $arrValues;
        	$insert = $this->PDO->prepare($this->strquery);
        	$resInsert = $insert->execute($this->arrValues);
        	if($resInsert)
	        {
	        	$lastInsert = $this->PDO->lastInsertId();
	        }else{
	        	$lastInsert = 0;
	        }
	        return $lastInsert; 
		}
		//Busca un registro
		public function select(string $query)
		{
			$this->strquery = $query;
        	$resultados = $this->PDO->prepare($this->strquery);
			$resultados->execute();
        	$datos = $resultados->fetch(PDO::FETCH_ASSOC);
        	return $datos;
		}
		//Devuelve todos los registros
		public function select_all(string $query)
		{
			$this->strquery = $query;
        	$resultados = $this->PDO->prepare($this->strquery);
			$resultados->execute();
        	$datos = $resultados->fetchall(PDO::FETCH_ASSOC);
        	return $datos;
		}
		//Actualiza registros
		public function update(string $query, array $arrValues)
		{
			$this->strquery = $query;
			$this->arrValues = $arrValues;
			$update = $this->PDO->prepare($this->strquery);
			$resExecute = $update->execute($this->arrValues);
	        return $resExecute;
		}
		//Eliminar un registros
		public function delete(string $query)
		{
			$this->strquery = $query;
        	$resultados = $this->PDO->prepare($this->strquery);
			$del = $resultados->execute();
        	return $del;
		}
        public function select_bind(string $query, array $arrValues)
        {
            $this->strquery = $query;
            $this->arrValues = $arrValues;
            $result = $this->PDO->prepare($this->strquery);
            $result->execute($this->arrValues);
            return $result->fetch(PDO::FETCH_ASSOC);
        }
            */
    }
?>