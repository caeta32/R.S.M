<?php
    class Conexion extends PDO {
        private $hostBd = 'localhost';
        private $nombreBd = 'informacionCentralizada';
        private $usuarioBd = 'root';
        private $passwordBd = 'agusabe12';

        public function __construct() {
            try {
                parent::__construct('mysql:host=' . $this->hostBd . ';dbname=' . $this->nombreBd . ';charset=utf8', $this->usuarioBd, $this->passwordBd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            }
        }
    }
?>