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

        public static function undefinedParams_estaciones($nombre, $localidad, $longitud, $latitud) {
            $result = array();

            if(!isset($nombre)) {
                $result[] = 'nombre';
            }
            if(!isset($localidad)) {
                $result[] = 'localidad';
            }
            if(!isset($longitud)) {
                $result[] = 'longitud';
            }
            if(!isset($latitud)) {
                $result[] = 'latitud';
            }

            if (empty($result))
                return false;
            else
                return $result;
        }

        /**
         * la cantidad de parámetros definidos es pasada por referencia para poder ser modificada
         * en el contexto en el que se esté utilizando la función.
         */
        public static function addColValueNameToQuery($sql, $name, &$quantityDefinedParams) {
            // Si ya se agregó uno antes, ingresa una coma.
            if($quantityDefinedParams > 0)
                $sql .= ', ';
            $quantityDefinedParams += 1;
            $sql .= $name.'=:'.$name;
            return $sql;
        }

        /**
         * Cálculo de las coordenadas decimales de un punto de destino, dadas unas
         * coordenadas decimales de un punto de inicio, una distancia en metros y 
         * un rumbo en grados (0° a 360°).
         * Las fórmulas utilizadas son para una forma esférica, no elipsoidal como es
         * realmente la forma de la Tierra, por lo que se esperan pequeños márgenes de error.
         * 
         * @see http://www.movable-type.co.uk/scripts/latlong.html
         * @see https://es.wikipedia.org/wiki/Rumbo
         * 
         * Las coordenadas así como el radio terrestre se asumen del sistema de coordenadas
         * geográficas WGS 84.
         * 
         * @see https://es.wikipedia.org/wiki/WGS84
         * @see https://docs.qgis.org/3.10/es/docs/gentle_gis_introduction/coordinate_reference_systems.html
         */
        public static function calcularCoordenadaDestino($latitud, $longitud, $rumbo, $distancia) {
            $D = $distancia / CustomConstants::RADIO_TERRESTRE;
            $R = deg2rad($rumbo);
            $lat = deg2rad($latitud);
            $long = deg2rad($longitud);

            $latDestino = asin((sin($lat) * cos($D)) + (cos($lat) * sin($D) * cos($R)));
            $longDestino = $long + atan2(sin($R) * sin($D) * cos($lat), cos($D) - (sin($lat) * sin($lat)));

            return ['latitud' => rad2deg($latDestino), 'longitud' => rad2deg($longDestino)];
        }

        /**
         * Se genera rumbo aleatorio, el rango es de 0° a 360° por defecto,
         * pudiendo pasar los grados máximos y mínimos deseados por parámetro.
         * @see https://es.wikipedia.org/wiki/Rumbo
         */
        public static function generarRumboAleatorio($min = 0, $max = 360) {
            return rand($min, $max);
        }

        /**
         * Se genera distancia aleatoria, donde la distancia mínima por
         * default es 1 y la máxima 10000 (asumiendo la unidad de medida en metros).
         */
        public static function generarDistanciaAleatoria($min = 1, $max = 10000) {
            return rand($min, $max);
        }

        /**
         * Cálculo de un punto de coordenadas aleatorio dentro de una localidad
         * dada.
         * 
         * La localidad debe ser uno de los 19 departamentros del Uruguay (se deben
         * incluir en el nombre de la localidad los acentos y espacios cuando
         * corresponda).
         * 
         * En caso de no reconocer la localidad recibida lanzará una excepción con
         * un mensaje preparado para ser enviado por la respuesta HTML en index.php.
         * @throws Excepction
         */
        public static function coordenadasAleatoriasDentroDe($localidad) {
            $rumbo = Utils::generarRumboAleatorio();
            switch ($localidad) {
                case 'Montevideo' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_1);
                    $latitud_inicio = CustomConstants::INICIO_LAT_MONTEVIDEO;
                    $longitud_inicio = CustomConstants::INICIO_LONG_MONTEVIDEO;
                    break;
                case 'Canelones' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_2);
                    $latitud_inicio = CustomConstants::INICIO_LAT_CANELONES;
                    $longitud_inicio = CustomConstants::INICIO_LONG_CANELONES;
                    break;
                case 'Rivera' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_2);
                    $latitud_inicio = CustomConstants::INICIO_LAT_RIVERA;
                    $longitud_inicio = CustomConstants::INICIO_LONG_RIVERA;
                    break;
                case 'Colonia' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_2);
                    $latitud_inicio = CustomConstants::INICIO_LAT_COLONIA;
                    $longitud_inicio = CustomConstants::INICIO_LONG_COLONIA;
                    break;
                case 'San José' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_2);
                    $latitud_inicio = CustomConstants::INICIO_LAT_SAN_JOSE;
                    $longitud_inicio = CustomConstants::INICIO_LONG_SAN_JOSE;
                    break;
                case 'Flores' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_2);
                    $latitud_inicio = CustomConstants::INICIO_LAT_FLORES;
                    $longitud_inicio = CustomConstants::INICIO_LONG_FLORES;
                    break;
                case 'Maldonado' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_2);
                    $latitud_inicio = CustomConstants::INICIO_LAT_MALDONADO;
                    $longitud_inicio = CustomConstants::INICIO_LONG_MALDONADO;
                    break;
                case 'Rocha' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_3);
                    $latitud_inicio = CustomConstants::INICIO_LAT_ROCHA;
                    $longitud_inicio = CustomConstants::INICIO_LONG_ROCHA;
                    break;
                case 'Treinta y Tres' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_3);
                    $latitud_inicio = CustomConstants::INICIO_LAT_TREINTA_Y_TRES;
                    $longitud_inicio = CustomConstants::INICIO_LONG_TREINTA_Y_TRES;
                    break;
                case 'Cerro Largo' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_3);
                    $latitud_inicio = CustomConstants::INICIO_LAT_CERRO_LARGO;
                    $longitud_inicio = CustomConstants::INICIO_LONG_CERRO_LARGO;
                    break;
                case 'Río Negro' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_3);
                    $latitud_inicio = CustomConstants::INICIO_LAT_RIO_NEGRO;
                    $longitud_inicio = CustomConstants::INICIO_LONG_RIO_NEGRO;
                    break;
                case 'Soriano' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_3);
                    $latitud_inicio = CustomConstants::INICIO_LAT_SORIANO;
                    $longitud_inicio = CustomConstants::INICIO_LONG_SORIANO;
                    break;
                case 'Durazno' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_3);
                    $latitud_inicio = CustomConstants::INICIO_LAT_DURAZNO;
                    $longitud_inicio = CustomConstants::INICIO_LONG_DURAZNO;
                    break;
                case 'Lavalleja' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_3);
                    $latitud_inicio = CustomConstants::INICIO_LAT_LAVALLEJA;
                    $longitud_inicio = CustomConstants::INICIO_LONG_LAVALLEJA;
                    break;
                case 'Tacuarembó' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_4);
                    $latitud_inicio = CustomConstants::INICIO_LAT_TACUAREMBO;
                    $longitud_inicio = CustomConstants::INICIO_LONG_TACUAREMBO;
                    break;
                case 'Artigas' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_4);
                    $latitud_inicio = CustomConstants::INICIO_LAT_ARTIGAS;
                    $longitud_inicio = CustomConstants::INICIO_LONG_ARTIGAS;
                    break;
                case 'Salto' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_4);
                    $latitud_inicio = CustomConstants::INICIO_LAT_SALTO;
                    $longitud_inicio = CustomConstants::INICIO_LONG_SALTO;
                    break;
                case 'Paysandú' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_4);
                    $latitud_inicio = CustomConstants::INICIO_LAT_PAYSANDU;
                    $longitud_inicio = CustomConstants::INICIO_LONG_PAYSANDU;
                    break;
                case 'Florida' :
                    $distancia = Utils::generarDistanciaAleatoria(1, CustomConstants::DIST_MAX_4);
                    $latitud_inicio = CustomConstants::INICIO_LAT_FLORIDA;
                    $longitud_inicio = CustomConstants::INICIO_LONG_FLORIDA;
                    break;
                default :
                    // ERROR DE PETICIÓN
                    $message = array();
                    $message[] = '422 Unprocessable Entity';
                    $message[] = 'No se reconoció la localidad recibida';
                    throw new Exception(implode(':', $message));
            }
            return Utils::calcularCoordenadaDestino($latitud_inicio, $longitud_inicio, $rumbo, $distancia);
        }
    }
?>