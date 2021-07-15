<?php
    class CustomConstants {
        // Constantes de base de datos.
        // --------------------------------------------------------
        public const HOST_DATA_BASE = 'localhost';
        public const NAME_DATA_BASE = 'informacionCentralizada';
        public const USER_DATA_BASE = 'root';
        public const PASSWORD_DATA_BASE = '';
        // --------------------------------------------------------
        
        // Constantes relacionadas con las funcionalidades de coordenadas.
        // --------------------------------------------------------
        /**
         * Radio ecuatorial en metros basado en el sistema de coordenadas geográficas WGS 84.
         * @see https://es.wikipedia.org/wiki/WGS84
         * @see https://docs.qgis.org/3.10/es/docs/gentle_gis_introduction/coordinate_reference_systems.html
         * */
        public const RADIO_TERRESTRE = 6378136.0;

        /**
         * Constantes de puntos de coordenadas usadas como punto de inicio
         * para el cálculo del punto de destino (que será utilizado como
         * ubicación generada automática y aleatoriamente por el sistema
         * para cada estación que se registre).
         */
        public const DIST_MAX_1 = 6600; // Metros.

        public const INICIO_LAT_MONTEVIDEO = -34.82579;
        public const INICIO_LONG_MONTEVIDEO = -56.16746;

        public const DIST_MAX_2 = 23550; // Metros.

        public const INICIO_LAT_CANELONES = -34.50253;
        public const INICIO_LONG_CANELONES = -55.85913;

        public const INICIO_LAT_RIVERA = -31.52327;
        public const INICIO_LONG_RIVERA = -55.35527;

        public const INICIO_LAT_COLONIA = -34.16487;
        public const INICIO_LONG_COLONIA = -57.60199;

        public const INICIO_LAT_SAN_JOSE = -34.33911;
        public const INICIO_LONG_SAN_JOSE = -56.74643;

        public const INICIO_LAT_FLORES = -33.60683;
        public const INICIO_LONG_FLORES = -56.87895;

        public const INICIO_LAT_MALDONADO = -34.63526;
        public const INICIO_LONG_MALDONADO = -54.83627;

        public const DIST_MAX_3 = 31000; // Metros.

        public const INICIO_LAT_ROCHA = -33.90466;
        public const INICIO_LONG_ROCHA = -54.04316;

        public const INICIO_LAT_TREINTA_Y_TRES = -33.07214;
        public const INICIO_LONG_TREINTA_Y_TRES = -54.29062;

        public const INICIO_LAT_CERRO_LARGO = -32.38898;
        public const INICIO_LONG_CERRO_LARGO = -54.20731;

        public const INICIO_LAT_RIO_NEGRO = -32.7313;
        public const INICIO_LONG_RIO_NEGRO = -57.27387;

        public const INICIO_LAT_SORIANO = -33.52623;
        public const INICIO_LONG_SORIANO = -57.70233;

        public const INICIO_LAT_DURAZNO = -32.97117;
        public const INICIO_LONG_DURAZNO = -55.6959;

        public const INICIO_LAT_LAVALLEJA = -33.74605;
        public const INICIO_LONG_LAVALLEJA = -54.89405;

        public const DIST_MAX_4 = 37150; // Metros.

        public const INICIO_LAT_TACUAREMBO = -32.13609;
        public const INICIO_LONG_TACUAREMBO = -55.78213;

        public const INICIO_LAT_ARTIGAS = -30.62151;
        public const INICIO_LONG_ARTIGAS = -56.92557;

        public const INICIO_LAT_SALTO = -31.41957;
        public const INICIO_LONG_SALTO = -56.79271;

        public const INICIO_LAT_PAYSANDU = -32.05493;
        public const INICIO_LONG_PAYSANDU = -57.57549;

        public const INICIO_LAT_FLORIDA = -33.83648;
        public const INICIO_LONG_FLORIDA = -55.99071;
        // --------------------------------------------------------
    }
?>