<?php
    include './Conexion.php';

    class WebService {
// Insertar Nuevos Registros Metereológicos ------------
        public function insertarRegistrosMetereologicos(
            $temp, $humedad, $presion, $velocidadViento, $dirViento, $radiacionSolar, $radiacionUV, $indicePluviometrico, $idEstacion
        ) {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
            
                // Se almacena la sentencia.
                $sql = 'INSERT INTO datosEstaciones (temperatura, humedad, presion, velocidadViento, direccionViento, radiacionSolar, radiacionIndiceUV, indicePluviometrico, idEstacion) VALUES (:temperatura, :humedad, :presion, :velocidadViento, :direccionViento, :radiacionSolar, :radiacionIndiceUV, :indicePluviometrico, :idEstacion)';
                // Se prepara la sentencia.
                $statement = $db->prepare($sql);

                // Se pasan los parámetros a la sentencia.
                $statement->bindValue(':temperatura', (($temp === null) || ($temp === "")) ? null : floatval($temp));
                $statement->bindValue(':humedad', (($humedad === null) || ($humedad === "")) ? null : floatval($humedad));
                $statement->bindValue(':presion', (($presion === null) || ($presion === "")) ? null : floatval($presion));
                $statement->bindValue(':velocidadViento', (($velocidadViento === null) || ($velocidadViento === "")) ? null : floatval($velocidadViento));
                $statement->bindValue(':direccionViento', $dirViento);
                $statement->bindValue(':radiacionSolar', (($radiacionSolar === null) || ($radiacionSolar === "")) ? null : floatval($radiacionSolar));
                $statement->bindValue(':radiacionIndiceUV', (($radiacionUV === null) || ($radiacionUV === "")) ? null : floatval($radiacionUV));
                $statement->bindValue(':indicePluviometrico', (($indicePluviometrico === null) || ($indicePluviometrico === "")) ? null : floatval($indicePluviometrico));
                $statement->bindValue(':idEstacion', $idEstacion);
                // Se ejecuta la sentencia.
                $statement->execute();
                // Finalización exitosa.
                return 'OK';
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            }
        }
// FIN Insertar Nuevos Registros Metereológicos ------------

// Insertar Nuevos Registros de un Usuario ------------
        public function ingresarUsuario($email, $nombre, $contrasenia, $idReducido, $nivelAcceso) {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
                // Para saber si existe el registro.
                $sql = 'SELECT * FROM usuarios WHERE email=:email OR idReducido=:idReducido';
                $statement = $db->prepare($sql);
                $statement->bindValue(':email', $email);
                $statement->bindValue(':idReducido', $idReducido);
                $statement->execute();
                if ($statement->rowCount() === 0) {
                    // Se almacena la sentencia.
                    $sql = 'INSERT INTO usuarios (email, nombre, contrasenia, idReducido, nivelAcceso) VALUES (:email, :nombre, :contrasenia, :idReducido, :nivelAcceso)';
                    // Se prepara la sentencia.
                    $statement = $db->prepare($sql);
                    // Se pasan los parámetros a la sentencia.
                    $statement->bindValue(':email', $email);
                    $statement->bindValue(':nombre', $nombre);
                    $statement->bindValue(':contrasenia', $contrasenia);
                    $statement->bindValue(':idReducido', $idReducido);
                    $statement->bindValue(':nivelAcceso', $nivelAcceso);
                    // Se ejecuta la sentencia.
                    $statement->execute();
                    // Finalización exitosa.
                    return 'OK';

                } else {
                    $message = array();
                    $message[] = '409 Conflict';
                    $message[] = 'Email o idReducido ya registrados';
                    throw new Exception(implode(':', $message));
                }
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
                exit;
            }
        }
// FIN Insertar Nuevos Registros de un Usuario -------------

// Insertar Nuevos Registros de una Estación ------------
        /**
         * Se ingresa una nueva estación, donde su id es generado automáticamente
         * y la ubicación geográfica en coordenadas decimales es generada 
         * aleatoriamente dentro de la localidad recibida como parámetro.
         */
        public function ingresarEstacion($id, $nombre, $localidad) {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
                // Para saber si existe el registro.
                $sql = 'SELECT * FROM estaciones WHERE nombre=:nombre OR id=:id';
                $statement = $db->prepare($sql);
                $statement->bindValue(':id', $id);
                $statement->bindValue(':nombre', $nombre);
                $statement->execute();
                if ($statement->rowCount() === 0) {
                    /**
                     * En caso de recibir el id con algún valor (o vacío) se le asignará
                     * null por defecto para que se auto-incremente por el gestor de la base
                     * de datos, debido a que éste debe ser generado automáticamente.
                     */
                    if (isset($id))
                        $id = null;
                    
                    // CÁLCULO DEL PUNTO DE COORDENADAS ALEATORIO (Punto de destino).
                    $puntoDestino = Utils::coordenadasAleatoriasDentroDe($localidad);

                    // Se almacena la sentencia.
                    $sql = 'INSERT INTO estaciones (id, nombre, localidad, longitud, latitud) VALUES (:id, :nombre, :localidad, :longitud, :latitud)';
                    // Se prepara la sentencia.
                    $statement = $db->prepare($sql);
                    // Se pasan los parámetros a la sentencia.
                    $statement->bindValue(':id', $id);
                    $statement->bindValue(':nombre', $nombre);
                    $statement->bindValue(':localidad', $localidad);
                    $statement->bindValue(':longitud', $puntoDestino['longitud']);
                    $statement->bindValue(':latitud', $puntoDestino['latitud']);
                    // Se ejecuta la sentencia.
                    $statement->execute();
                    // Finalización exitosa.
                    return 'OK';

                } else {
                    $message = array();
                    $message[] = '409 Conflict';
                    $message[] = 'nombre o id ya registrados';
                    throw new Exception(implode(':', $message));
                }
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
                exit;
            }
        }
// FIN Insertar Nuevos Registros de una Estación -------------

// Actualizar datos de un usuario ------------
        /**
         * $id puede ser el email o identificador único del usuario.
         * Se asume que $id está definido ya que es el único parámetro realmente necesario para
         * poder realizar la actualización.
         * Los parámetros que no lleguen definidos, se asumirá que no se quieren actualizar,
         * por lo que se mantendrá el valor que estaba registrado previamente, a excepción del id.
         */
        public function actualizarDatosUsuario($id, $nombre, $contrasenia, $nivelAcceso) {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
                // Para saber si existe el registro.
                $sql = 'SELECT * FROM usuarios WHERE email=:id OR idReducido=:id';
                $statement = $db->prepare($sql);
                $statement->bindValue(':id', $id);
                $statement->execute();                
                if ($statement->rowCount() > 0) {
                    // Sentencia.
                    $sql = 'UPDATE usuarios SET ';
                    // Se agregan los atributos que se quieren actualizar.
                    $quantityDefinedParams = 0;
                    if(isset($nombre)) {
                        $sql = Utils::addColValueNameToQuery($sql, 'nombre', $quantityDefinedParams);
                    }
                    if(isset($contrasenia)) {
                        $sql = Utils::addColValueNameToQuery($sql, 'contrasenia', $quantityDefinedParams);
                    }
                    if(isset($nivelAcceso)) {
                        $sql = Utils::addColValueNameToQuery($sql, 'nivelAcceso', $quantityDefinedParams);
                    }
                    /**
                     * Si habían parámetros para actualizar definidos, entonces
                     * hay datos para actualizar, por lo que se continúa con las
                     * operaciones y se ejecuta la sentencia.
                     */
                    if($quantityDefinedParams > 0) {
                        $sql .= ' WHERE email=:id OR idReducido=:id';
                        // Se prepara la sentencia.
                        $statement = $db->prepare($sql);
                        // Se pasan los parámetros a la sentencia.
                        $statement->bindValue(':id', $id);

                        if(isset($nombre))
                            $statement->bindValue(':nombre', $nombre);
                        if(isset($contrasenia))
                            $statement->bindValue(':contrasenia', $contrasenia);
                        if(isset($nivelAcceso))
                            $statement->bindValue(':nivelAcceso', $nivelAcceso);

                        // Se ejecuta la sentencia.
                        $statement->execute();

                        $result = 'OK';
                    // De lo contrario, no se realizan más acciones y se devuelve
                    // un mensaje indicando que no ha habido errores pero no se han
                    // realizado acciones.
                    } else {
                        $result = 'OK without actions';
                    }
                } else {
                    $message = array();
                    $message[] = '404 Not Found';
                    $message[] = 'Email o id no registrado';
                    throw new Exception(implode(':', $message));
                }

                return $result;

            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
                exit;
            }
        }
// FIN Actualizar datos de un usuario ------------

// Actualizar datos de una estación ------------
        /**
         * Se asume que $id está definido ya que es el único parámetro realmente necesario para
         * poder realizar la actualización.
         * Los parámetros que no lleguen definidos o lleguen vacíos, se asumirá que no se quieren actualizar,
         * por lo que se mantendrá el valor que estaba registrado previamente, a excepción del id.
         */
        public function actualizarEstacion($id, $nombre, $localidad, $longitud, $latitud) {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
                // Para saber si existe el registro.
                $sql = 'SELECT * FROM estaciones WHERE id=:id';
                $statement = $db->prepare($sql);
                $statement->bindValue(':id', $id);
                $statement->execute();                
                if ($statement->rowCount() > 0) {
                    // Sentencia.
                    $sql = 'UPDATE estaciones SET ';

                    // Se agregan los atributos que se quieren actualizar.

                    // La cantidad de parámetros definidos es modificada por referencia por la función
                    // Utils::addColValueNameToQuery().
                    $quantityDefinedParams = 0;
                    if(isset($nombre) && ($nombre !== "")) {
                        $sql = Utils::addColValueNameToQuery($sql, 'nombre', $quantityDefinedParams);
                    }
                    if(isset($localidad) && ($localidad !== "")) {
                        $sql = Utils::addColValueNameToQuery($sql, 'localidad', $quantityDefinedParams);
                    }
                    if(isset($longitud) && ($longitud !== "")) {
                        $sql = Utils::addColValueNameToQuery($sql, 'longitud', $quantityDefinedParams);
                    }
                    if(isset($latitud) && ($latitud !== "")) {
                        $sql = Utils::addColValueNameToQuery($sql, 'latitud', $quantityDefinedParams);
                    }
                    // Si habían parámetros para actualizar definidos, entonces
                    // hay datos para actualizar, por lo que se ejecuta la sentencia.
                    if($quantityDefinedParams > 0) {
                        $sql .= ' WHERE id=:id';
                        // Se prepara la sentencia.
                        $statement = $db->prepare($sql);
                        // Se pasan los parámetros a la sentencia.
                        $statement->bindValue(':id', $id);
                        
                        if(isset($nombre) && ($nombre !== "")) 
                            $statement->bindValue(':nombre', $nombre);
                        if(isset($localidad) && ($localidad !== "")) 
                            $statement->bindValue(':localidad', $localidad);
                        if(isset($longitud) && ($longitud !== "")) 
                            $statement->bindValue(':longitud', $longitud);
                        if(isset($latitud) && ($latitud !== "")) 
                            $statement->bindValue(':latitud', $latitud);

                        // Se ejecuta la sentencia.
                        $statement->execute();

                        $result = 'OK';
                    // De lo contrario, no se realizan más acciones y se devuelve
                    // un mensaje indicando que no ha habido errores pero no se han
                    // realizado acciones.
                    } else {
                        $result = 'OK without actions';
                    }
                } else {
                    $message = array();
                    $message[] = '404 Not Found';
                    $message[] = 'id no registrado';
                    throw new Exception(implode(':', $message));
                }

                return $result;

            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
                exit;
            }
        }
// FIN Actualizar datos de una estación ------------

// Eliminar datos de una estación ------------
        /** 
         * Se asume que $id está definido.
         */
        public function eliminarDatosDeEstacion($id) {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
                // Para saber si existe el registro.
                $sql = 'SELECT * FROM datosEstaciones WHERE idEstacion=:id';
                $statement = $db->prepare($sql);
                $statement->bindValue(':id', $id);
                $statement->execute();                
                if ($statement->rowCount() > 0) {
                    // Sentencia.
                    $sql = 'DELETE FROM datosEstaciones WHERE idEstacion=:id';

                    // Se prepara la sentencia.
                    $statement = $db->prepare($sql);
                    // Se pasan los parámetros a la sentencia.
                    $statement->bindValue(':id', $id);
                
                    // Se ejecuta la sentencia.
                    $statement->execute();

                    $result = 'OK';

                } else {
                    $message = array();
                    $message[] = '404 Not Found';
                    $message[] = 'id no registrado';
                    throw new Exception(implode(':', $message));
                }

                return $result;

            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
                exit;
            }
        }
// FIN Eliminar datos de una estación ------------

// Eliminar Usuario ------------
        /**
         * Se asume que $email está definido.
         */
        public function eliminarUsuario($email) {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
                // Para saber si existe el registro.
                $sql = 'SELECT * FROM usuarios WHERE email=:email';
                $statement = $db->prepare($sql);
                $statement->bindValue(':email', $email);
                $statement->execute();                
                if ($statement->rowCount() > 0) {
                    // Sentencia.
                    $sql = 'DELETE FROM usuarios WHERE email=:email';

                    // Se prepara la sentencia.
                    $statement = $db->prepare($sql);
                    // Se pasan los parámetros a la sentencia.
                    $statement->bindValue(':email', $email);
                
                    // Se ejecuta la sentencia.
                    $statement->execute();

                    $result = 'OK';

                } else {
                    $message = array();
                    $message[] = '404 Not Found';
                    $message[] = 'id no registrado';
                    throw new Exception(implode(':', $message));
                }

                return $result;

            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
                exit;
            }
        }
// FIN Eliminar Usuario ------------

// Eliminar Estación ------------
        /**
         * Se asume que $id está definido.
         */
        public function eliminarEstacion($id) {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
                // Para saber si existe el registro.
                $sql = 'SELECT * FROM estaciones WHERE id=:id';
                $statement = $db->prepare($sql);
                $statement->bindValue(':id', $id);
                $statement->execute();                
                if ($statement->rowCount() > 0) {
                    // Sentencia.
                    $sql = 'DELETE FROM estaciones WHERE id=:id';

                    // Se prepara la sentencia.
                    $statement = $db->prepare($sql);
                    // Se pasan los parámetros a la sentencia.
                    $statement->bindValue(':id', $id);
                
                    // Se ejecuta la sentencia.
                    $statement->execute();

                    $result = 'OK';

                } else {
                    $message = array();
                    $message[] = '404 Not Found';
                    $message[] = 'id no registrado';
                    throw new Exception(implode(':', $message));
                }

                return $result;

            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
                exit;
            }
        }
// FIN Eliminar Estación ------------

// Ver todos los datos metereológicos -------------
        public function verTodos_DatosEstaciones() {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
                $sql = 'SELECT * FROM datosEstaciones';
                $statement = $db->prepare($sql);
                $statement->execute();
                if ($statement->rowCount() > 0) {
                    $statement->setFetchMode(PDO::FETCH_ASSOC);
                    return $statement->fetchAll();
                } else {
                    $message = array();
                    $message[] = '404 Not Found';
                    $message[] = 'No se encontraron registros';
                    throw new Exception(implode(':', $message));
                    exit;
                }
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
                exit;
            }
        }
// FIN Ver todos los datos metereológicos -------------

// Ver todos los datos de todos los usuarios -------------
        public function verTodos_Usuarios() {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
                $sql = 'SELECT * FROM usuarios';
                $statement = $db->prepare($sql);
                $statement->execute();
                if ($statement->rowCount() > 0) {
                    $statement->setFetchMode(PDO::FETCH_ASSOC);
                    return $statement->fetchAll();
                } else {
                    $message = array();
                    $message[] = '404 Not Found';
                    $message[] = 'No se encontraron registros';
                    throw new Exception(implode(':', $message));
                    exit;
                }
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
                exit;
            }
        }
// FIN Ver todos los datos de todos los usuarios -------------

// Ver todos los datos de todas las estaciones -------------
        public function verTodos_Estaciones() {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
                $sql = 'SELECT * FROM estaciones';
                $statement = $db->prepare($sql);
                $statement->execute();
                if ($statement->rowCount() > 0) {
                    $statement->setFetchMode(PDO::FETCH_ASSOC);
                    return $statement->fetchAll();
                } else {
                    $message = array();
                    $message[] = '404 Not Found';
                    $message[] = 'No se encontraron registros';
                    throw new Exception(implode(':', $message));
                    exit;
                }
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
                exit;
            }
        }
// FIN Ver todos los datos de todas las estaciones -------------

// Ver todos los datos metereológicos de una estación -------------
        public function verDatosDeEstacion($id) {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
                $sql = 'SELECT * FROM datosEstaciones WHERE idEstacion=:id';
                $statement = $db->prepare($sql);
                $statement->bindValue(':id', $id);
                $statement->execute();
                if ($statement->rowCount() > 0) {
                    $statement->setFetchMode(PDO::FETCH_ASSOC);
                    return $statement->fetchAll();
                } else {
                    $message = array();
                    $message[] = '404 Not Found';
                    $message[] = 'id no registrado';
                    throw new Exception(implode(':', $message));
                    exit;
                }
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
                exit;
            }
        }
// FIN Ver todos los datos metereológicos de una estación -------------

// Ver todos los datos de todas las estaciones -------------
        public function verEstacion($id) {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
                $sql = 'SELECT * FROM estaciones WHERE id=:id';
                $statement = $db->prepare($sql);
                $statement->bindValue(':id', $id);
                $statement->execute();
                if ($statement->rowCount() > 0) {
                    $statement->setFetchMode(PDO::FETCH_ASSOC);
                    return $statement->fetch();
                } else {
                    $message = array();
                    $message[] = '404 Not Found';
                    $message[] = 'id no registrado';
                    throw new Exception(implode(':', $message));
                }
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
                exit;
            }
        }
// FIN Ver todos los datos de todas las estaciones -------------

// Ver todos los datos de todas las estaciones -------------
        public function verUsuario($id) {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
                $sql = 'SELECT * FROM usuarios WHERE email=:id OR idReducido=:id';
                $statement = $db->prepare($sql);
                $statement->bindValue(':id', $id);
                $statement->execute();
                if ($statement->rowCount() > 0) {
                    $statement->setFetchMode(PDO::FETCH_ASSOC);
                    return $statement->fetch();
                } else {
                    $message = array();
                    $message[] = '404 Not Found';
                    $message[] = 'Email o id no registrados';
                    throw new Exception(implode(':', $message));
                }
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
                exit;
            }
        }
// FIN Ver todos los datos de todas las estaciones -------------

// Obtener estaciones más cercanas a un punto de coordenadas geográficas Latitud/Longitud. ----------------------
        /**
         * En caso de que las estaciones más cercanas sean más de una (tengan igual distancia 
         * (más corta) desde las coordenadas recibidas) devuelve la que tenga el id menor.
         * 
         * Se retorna la distancia con 6 decimales como máximo y en metros mientras no se 
         * superen los mil metros, en caso de superarse, se devuelve en kilómetros.
         * 
         * Se asumen coordenadas de latitud y longitud en grados decimales así como el 
         * uso del radio ecuatorial terrestre, todo basado en el sistema de coordenadas geográficas 
         * WGS 84.
         * @see https://es.wikipedia.org/wiki/WGS84
         * @see https://docs.qgis.org/3.10/es/docs/gentle_gis_introduction/coordinate_reference_systems.html
         * Se utiliza la fórmula de Haversine, los márgenes de error esperados son entre 0,3% y 0,55%.
         * @see http://www.movable-type.co.uk/scripts/latlong.html
         * @see https://en.wikipedia.org/wiki/Haversine_formula
         */
        public function estacionMasCercana($idEstacion, $latitud_param, $longitud_param) {
            try {
                $radioTerrestre = CustomConstants::RADIO_TERRESTRE; // Radio ecuatorial en metros.
                $db = new Conexion();

                //=========================================================================================
                // Obtención de información de la estación o coordenadas, dependiendo los parámetros recibidos.

                // Si se recibe un id se asume que se quiere conocer la estación más cercana a la estación
                // con dicho id (ignorando si se recibieron o no latitud_param y longitud_param).
                if(isset($idEstacion) && ($idEstacion !== "")) {
                    $sql = 'SELECT * FROM estaciones WHERE id=:id';
                    $statement = $db->prepare($sql);
                    $statement->bindValue(':id', $idEstacion);
                    $statement->execute();
                    if ($statement->rowCount() > 0) {
                        $result = $statement->fetch(PDO::FETCH_ASSOC);
                        $id = $result['id'];
                        // Se usarán las coordenadas de la estación con el id recibido.
                        $latitud = $result['latitud'];
                        $longitud = $result['longitud'];
                    } else {
                        $message = array();
                        $message[] = '404 Not Found';
                        $message[] = 'id no registrado';
                        throw new Exception(implode(':', $message));
                    }
                // En caso de no recibir id, se pasa a comprobar si se recibieron coordenadas; si es así, se
                // asume que se quiere conocer la estación más cercana a un punto de coordenadas cualquiera.
                } elseif (isset($latitud_param) && ($latitud_param !== "") && isset($longitud_param) && ($longitud_param !== "")) {
                    // el id que se agregará al parámetro de la consulta se asigna como cadena vacía, para que no
                    // concuerde con ningún id de estación registrado y por lo tanto no afecte el resultado.
                    $id = "";
                    // Se usarán las coordenadas recibidas.
                    $latitud = $latitud_param;
                    $longitud = $longitud_param;
                } else {
                    // ERROR DE PETICIÓN
                    $message = array();
                    $message[] = '400 Bad Request';
                    $message[] = "no se recibio id ni coordenadas (latitud y longitud)";
                    throw new Exception(implode(':', $message));
                }
                //=========================================================================================
                // =====================================================================
                // CONSULTA PARA OBTENER LA ESTACIÓN MÁS CERCANA HACIENDO USO DE LA FÓRMULA
                // DE HAVERSINE PARA EL CÁLCULO DE DISTANCIA.
                $sql = 
                'SELECT 
                *, 
                2 * :radioTerrestre * (
                                    asin( sqrt(
                                                    pow(
                                                        sin(
                                                                (radians(latitud) - radians(:lat))/2
                                                        )
                                                    , 2) +
                                                    (
                                                        cos(radians(:lat)) *
                                                        cos(radians(latitud)) *
                                                        pow(
                                                                sin(
                                                                    (radians(longitud) - radians(:long))/2
                                                            )
                                                        , 2)
                                                    )
                                            ) 
                                        )
                                ) AS distancia
                FROM
                    estaciones
                WHERE
                    id!=:id
                ORDER BY distancia, id ASC
                LIMIT 1';
                // =====================================================================
                $statement = $db->prepare($sql);
                $statement->bindValue(':radioTerrestre', $radioTerrestre);
                $statement->bindValue(':lat', $latitud);
                $statement->bindValue(':long', $longitud);
                $statement->bindValue(':id', $id);
                $statement->execute();
                if($statement->rowCount() > 0) {
                    $result = $statement->fetch(PDO::FETCH_ASSOC);
                    $result['distancia'] = strval(round($result['distancia'], 6));
                    if ($result['distancia'] > 1000) {// Si se supera el kilómetro
                        $result['distancia'] = strval(round($result['distancia'] / 1000, 6)); // Se convierte a kilómetro
                        $result['unidadDistancia'] = 'km';
                    } else {
                        $result['unidadDistancia'] = 'm';
                    }
                    return $result;
                } else {
                    $message = array();
                    $message[] = '404 Not Found';
                    $message[] = 'no se encontraron otras estaciones';
                    throw new Exception(implode(':', $message));    
                }
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
                exit;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
                exit;
            }
        }
// FIN Obtener estaciones más cercanas a un punto de coordenadas geográficas Latitud/Longitud. ----------------------

    }
?>
