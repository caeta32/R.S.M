<?php
    class Conexion extends PDO {
        public function __construct() {
            try {
                parent::__construct('mysql:host=' . CustomConstants::HOST_DATA_BASE . ';dbname=' . CustomConstants::NAME_DATA_BASE . ';charset=utf8', CustomConstants::USER_DATA_BASE, CustomConstants::PASSWORD_DATA_BASE, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            }
        }
    }
?>