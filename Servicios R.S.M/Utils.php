<?php
    class Utils {

        public static function undefinedParams_datosEstacion($temperatura, $humedad, $presion, $velocidadViento, $direccionViento, $radiacionSolar, $radiacionIndiceUV, $indicePluviometrico) {
            $result = array();

            if(!isset($temperatura)) {
                $result[] = 'temperatura';
            }
            if(!isset($humedad)) {
                $result[] = 'humedad';
            }
            if(!isset($presion)) {
                $result[] = 'presion';
            }
            if(!isset($velocidadViento)) {
                $result[] = 'velocidadViento';
            }
            if(!isset($direccionViento)) {
                $result[] = 'direccionViento';
            }
            if(!isset($radiacionSolar)) {
                $result[] = 'radiacionSolar';
            }
            if(!isset($radiacionIndiceUV)) {
                $result[] = 'radiacionIndiceUV';
            }
            if(!isset($indicePluviometrico)) {
                $result[] = 'indicePluviometrico';
            }

            if (empty($result))
                return false;
            else
                return $result;
        }
        
        public static function undefinedParams_usuarios($idReducido, $nombre) {
            $result = array();

            if(!isset($idReducido)) {
                $result[] = 'idReducido';
            }
            if(!isset($nombre)) {
                $result[] = 'nombre';
            }

            if (empty($result))
                return false;
            else
                return $result;
        }

        public static function undefinedParams_estaciones($nombre, $localidad) {
            $result = array();

            if(!isset($nombre)) {
                $result[] = 'nombre';
            }
            if(!isset($localidad)) {
                $result[] = 'localidad';
            }

            if (empty($result))
                return false;
            else
                return $result;
        }
    }
?>