<?php
    include './Conexion.php';
    function OpenCon() {
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "";
        $db = "informacioncentralizada";
        $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
        
        return $conn;
    }
    function CloseCon($conn) {
        $conn -> close();
    }
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
        public function ingresarUsuario($email, $contrasenia, $nombre, $apellido, $nivelAcceso) {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
                // Para saber si existe el registro.
                $sql = 'SELECT * FROM usuarios WHERE email=:email';
                $statement = $db->prepare($sql);
                $statement->bindValue(':email', $email);
                $statement->execute();
                if ($statement->rowCount() === 0) {
                    // Se almacena la sentencia.
                    $sql = 'INSERT INTO usuarios (email, contrasenia, nombre, apellido, nivelAcceso) VALUES (:email, :contrasenia, :nombre, :apellido, :nivelAcceso)';
                    // Se prepara la sentencia.
                    $statement = $db->prepare($sql);
                    // Se pasan los parámetros a la sentencia.
                    $statement->bindValue(':email', $email);
                    $statement->bindValue(':nombre', $nombre);
                    $statement->bindValue(':contrasenia', $contrasenia);
                    $statement->bindValue(':apellido', $apellido);
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
        public function ingresarEstacion($id, $nombre, $localidad, $emailCreador) {
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
                    $sql = 'INSERT INTO estaciones (id, nombre, localidad, longitud, latitud, email) VALUES (:id, :nombre, :localidad, :longitud, :latitud, :emailCreador)';
                    // Se prepara la sentencia.
                    $statement = $db->prepare($sql);
                    // Se pasan los parámetros a la sentencia.
                    $statement->bindValue(':id', $id);
                    $statement->bindValue(':nombre', $nombre);
                    $statement->bindValue(':localidad', $localidad);
                    $statement->bindValue(':longitud', $puntoDestino['longitud']);
                    $statement->bindValue(':latitud', $puntoDestino['latitud']);
                    $statement->bindValue(':emailCreador', $emailCreador);
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
                $sql = 'SELECT * FROM usuarios WHERE email=:id';
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
        
            public function verEstacionporMail($emailCreador) {
            try {
                // Se conecta a la base de datos.
                $db = new Conexion();
                $sql = 'SELECT * FROM estaciones WHERE email=:emailCreador';
                $statement = $db->prepare($sql);
                $statement->bindValue(':emailCreador', $emailCreador);
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
// -------------------- PARSEAR FECHAS DE LA ESTACION POR DIA ------------------------ //

        public function promedioDia($id) {
            $conn = OpenCon();
            $result = $conn->query("SELECT fechaCreado FROM datosestaciones where idEstacion = " .$id);
            $temps = [];
            $humedad = [];
            $presion = [];
            $velocidadViento = [];
            $direccionViento = [];
            $radiacionSolar = [];
            $radiacionIndiceUV = [];
            $indicePluviometrico = [];
            $fechas_del_dia = [];
            $fechas = [];

            while ($row = $result->fetch_assoc()) {
                array_push($fechas, $row['fechaCreado']);
            }
            foreach ($fechas as $individual) {
                if(date("d") == date("d", strtotime($individual))){
                    array_push($fechas_del_dia, $individual);
                }
            }

            $fechaSeparada = explode(" ", $fechas_del_dia[0]);
            $fechaSinHora = $fechaSeparada[0];
            $result = $conn->query("SELECT * FROM datosestaciones where fechaCreado LIKE '". $fechaSinHora ."%' and idEstacion = 3;");

            
            while ($row = $result->fetch_assoc()) {
                array_push($temps, $row['temperatura']);
                array_push($humedad, $row['humedad']);
                array_push($presion, $row['presion']);
                array_push($velocidadViento, $row['velocidadViento']);
                array_push($direccionViento, $row['direccionViento']);
                array_push($radiacionSolar, $row['radiacionSolar']);
                array_push($radiacionIndiceUV, $row['radiacionIndiceUV']);
                array_push($indicePluviometrico, $row['indicePluviometrico']);
            }
            
            $datos_divididos_temp = array_chunk($temps, 24);
            $datos_divididos_hume = array_chunk($humedad, 24);
            $datos_divididos_pres = array_chunk($presion, 24);
            $datos_divididos_velviento = array_chunk($velocidadViento, 24);
            $datos_divididos_radsolar = array_chunk($radiacionSolar, 24);
            $datos_divididos_radsolaruv = array_chunk($radiacionIndiceUV, 24);
            $datos_divididos_indpluvio = array_chunk($indicePluviometrico, 24);
            $promedio_en_horas_temp = [];
            $promedio_en_horas_hume = [];
            $promedio_en_horas_pres = [];
            $promedio_en_horas_velviento = [];
            $promedio_en_horas_radsolar = [];
            $promedio_en_horas_radsolaruv = [];
            $promedio_en_horas_indpluvio = [];
        
            
            for($i = 0; $i < count($datos_divididos_temp); $i++) {
                array_push($promedio_en_horas_temp, array_sum($datos_divididos_temp[$i]) / count(array_filter($datos_divididos_temp[$i])));
            }
        
            for($i = 0; $i < count($datos_divididos_hume); $i++) {
                array_push($promedio_en_horas_hume, array_sum($datos_divididos_hume[$i]) / count(array_filter($datos_divididos_hume[$i])));
            }
            
            for($i = 0; $i < count($datos_divididos_pres); $i++) {
                array_push($promedio_en_horas_pres, array_sum($datos_divididos_pres[$i]) / count(array_filter($datos_divididos_pres[$i])));
            }
            
            for($i = 0; $i < count($datos_divididos_velviento); $i++) {
                array_push($promedio_en_horas_velviento, array_sum($datos_divididos_velviento[$i]) / count(array_filter($datos_divididos_velviento[$i])));
            }
        
            for($i = 0; $i < count($datos_divididos_radsolar); $i++) {
                array_push($promedio_en_horas_radsolar, array_sum($datos_divididos_radsolar[$i]) / count(array_filter($datos_divididos_radsolar[$i])));
            }
        
            for($i = 0; $i < count($datos_divididos_radsolaruv); $i++) {
                array_push($promedio_en_horas_radsolaruv, array_sum($datos_divididos_radsolaruv[$i]) / count(($datos_divididos_radsolaruv[$i])));
            }
        
            for($i = 0; $i < count($datos_divididos_indpluvio); $i++) {
                array_push($promedio_en_horas_indpluvio, array_sum($datos_divididos_indpluvio[$i]) / count(($datos_divididos_indpluvio[$i])));
            }
            
            $datos = array('temperatura' => $promedio_en_horas_temp,
            'humedad' => $promedio_en_horas_hume,
            'presion' => $promedio_en_horas_pres,
            'velviento' => $promedio_en_horas_velviento,
            'radsolar' => $promedio_en_horas_radsolar,
            'radsolaruv' => $promedio_en_horas_radsolaruv,
            'indpluvio' => $promedio_en_horas_indpluvio);
            return $datos;
        }
        
        // -------------------- PARSEAR FECHAS DE LA ESTACION POR DIA ------------------------ //

        //--------------------- PARSEAR FECHAS DE LA ESTACION POR SEMANA ---------------------//

        public function promedioSemana($id) {
            $temps = [];
            $humedad = [];
            $presion = [];
            $velocidadViento = [];
            $direccionViento = [];
            $radiacionSolar = [];
            $radiacionIndiceUV = [];
            $indicePluviometrico = [];
            $fechasSemanaSeparadas = [];
            $fechasSinHora = [];
            $fechasASacarRepeticion = [];
            $datosDividosPorDia = [];
            $arregloFinalSemanal = [];
            $promedioPorSemana = [];
            $conn = OpenCon();
            $result = $conn->query("SELECT fechaCreado FROM datosestaciones where idEstacion = " .$id);
            $fechas = [];
            $fechas_de_la_semana = [];

            while ($row = $result->fetch_assoc()) {
                array_push($fechas, $row['fechaCreado']);
            }
            foreach ($fechas as $individual) {
                if(date("W") == date("W", strtotime($individual))){
                    array_push($fechas_de_la_semana, $individual);
                }
            }
            
            for($i = 0; $i < count($fechas_de_la_semana); $i++) {
                array_push($fechasSemanaSeparadas, explode(" ", $fechas_de_la_semana[$i]));
            }
            for($i = 0; $i < count($fechasSemanaSeparadas); $i++) {
                array_push($fechasSinHora, explode(" ", $fechasSemanaSeparadas[$i][0]));
            }
        
            foreach($fechasSinHora as $fechas) {
                array_push($fechasASacarRepeticion, $fechas[0]);
            }
        
            $fechasSemanalesFinales = array_unique($fechasASacarRepeticion);
        
            foreach($fechasSemanalesFinales as $fecha) {
                $consultaPorCadaDia = $conn->query("SELECT * FROM datosestaciones where fechaCreado LIKE '". $fecha ."%' and idEstacion = 3;");
                while ($row = $consultaPorCadaDia->fetch_assoc()) {
                    array_push($temps, $row['temperatura']);
                    array_push($humedad, $row['humedad']);
                    array_push($presion, $row['presion']);
                    array_push($velocidadViento, $row['velocidadViento']);
                    array_push($direccionViento, $row['direccionViento']);
                    array_push($radiacionSolar, $row['radiacionSolar']);
                    array_push($radiacionIndiceUV, $row['radiacionIndiceUV']);
                    array_push($indicePluviometrico, $row['indicePluviometrico']);
                  }
                  array_push($temps, "EXIT");
                  array_push($humedad, "EXIT");
                  array_push($presion, "EXIT");
                  array_push($velocidadViento, "EXIT");
                  array_push($direccionViento, "EXIT");
                  array_push($radiacionSolar, "EXIT");
                  array_push($radiacionIndiceUV, "EXIT");
                  array_push($indicePluviometrico, "EXIT");
            }
            
            $datosDividosPorDia = [];
            $arregloFinalTemp = [];
            $arregloFinalHume = [];
            $arregloFinalPres = [];
            $arregloFinalvelViento = [];
            $arregloFinalradSolar = [];
            $arregloFinalradSolarUV = [];
            $arregloFinalindPluvio = [];
            $promedio_en_dias_temp = [];
            $promedio_en_dias_hume = [];
            $promedio_en_dias_pres = [];
            $promedio_en_dias_velviento = [];
            $promedio_en_dias_radsolar = [];
            $promedio_en_dias_radsolaruv = [];
            $promedio_en_dias_indpluvio = [];
        
            foreach($temps as $temp) {
                if($temp != "EXIT") {
                    array_push($datosDividosPorDia, $temp);
                } else {
                    array_push($arregloFinalTemp, $datosDividosPorDia);
                    $datosDividosPorDia = [];
                }
            }
        
            foreach($humedad as $hume) {
                if($hume != "EXIT") {
                    array_push($datosDividosPorDia, $hume);
                } else {
                    array_push($arregloFinalHume, $datosDividosPorDia);
                    $datosDividosPorDia = [];
                }
            }
        
            foreach($presion as $pres) {
                if($pres != "EXIT") {
                    array_push($datosDividosPorDia, $pres);
                } else {
                    array_push($arregloFinalPres, $datosDividosPorDia);
                    $datosDividosPorDia = [];
                }
            }
        
            foreach($velocidadViento as $velViento) {
                if($velViento != "EXIT") {
                    array_push($datosDividosPorDia, $velViento);
                } else {
                    array_push($arregloFinalvelViento, $datosDividosPorDia);
                    $datosDividosPorDia = [];
                }
            }
        
            foreach($radiacionSolar as $radsolar) {
                if($radsolar != "EXIT") {
                    array_push($datosDividosPorDia, $radsolar);
                } else {
                    array_push($arregloFinalradSolar, $datosDividosPorDia);
                    $datosDividosPorDia = [];
                }
            }
        
            foreach($radiacionIndiceUV as $raduv) {
                if($raduv != "EXIT") {
                    array_push($datosDividosPorDia, $raduv);
                } else {
                    array_push($arregloFinalradSolarUV, $datosDividosPorDia);
                    $datosDividosPorDia = [];
                }
            }
        
            foreach($indicePluviometrico as $indpluvio) {
                if($indpluvio != "EXIT") {
                    array_push($datosDividosPorDia, $indpluvio);
                } else {
                    array_push($arregloFinalindPluvio, $datosDividosPorDia);
                    $datosDividosPorDia = [];
                }
            }
        
            for($i = 0; $i < count($arregloFinalTemp); $i++) {
                array_push($promedio_en_dias_temp, array_sum($arregloFinalTemp[$i]) / count(array_filter($arregloFinalTemp[$i])));
           }
        
           for($i = 0; $i < count($arregloFinalHume); $i++) {
               array_push($promedio_en_dias_hume, array_sum($arregloFinalHume[$i]) / count(array_filter($arregloFinalHume[$i])));
           }
        
           for($i = 0; $i < count($arregloFinalPres); $i++) {
               array_push($promedio_en_dias_pres, array_sum($arregloFinalPres[$i]) / count(array_filter($arregloFinalPres[$i])));
           }
        
           for($i = 0; $i < count($arregloFinalvelViento); $i++) {
              array_push($promedio_en_dias_velviento, array_sum($arregloFinalvelViento[$i]) / count(array_filter($arregloFinalvelViento[$i])));
           }
        
           for($i = 0; $i < count($arregloFinalradSolar); $i++) {
           array_push($promedio_en_dias_radsolar, array_sum($arregloFinalradSolar[$i]) / count(array_filter($arregloFinalradSolar[$i])));
           }
        
           for($i = 0; $i < count($arregloFinalradSolarUV); $i++) {
           array_push($promedio_en_dias_radsolaruv, array_sum($arregloFinalradSolarUV[$i]) / count(($arregloFinalradSolarUV[$i])));
           }
        
           for($i = 0; $i < count($arregloFinalindPluvio); $i++) {
               array_push($promedio_en_dias_indpluvio, array_sum($arregloFinalindPluvio[$i]) / count(($arregloFinalindPluvio[$i])));
           }
        
           $datos = array('temperatura' => $promedio_en_dias_temp,
            'humedad' => $promedio_en_dias_hume,
            'presion' => $promedio_en_dias_pres,
            'velviento' => $promedio_en_dias_velviento,
            'radsolar' => $promedio_en_dias_radsolar,
            'radsolaruv' => $promedio_en_dias_radsolaruv,
            'indpluvio' => $promedio_en_dias_indpluvio);
           
            return $datos;
        }

//--------------------- PARSEAR FECHAS DE LA ESTACION POR SEMANA ---------------------//

// -------------------- PARSEAR FECHAS DE LA ESTACION POR MES ------------------------ //
        public function promedioMes($id) {
            $conn = OpenCon();
            $result = $conn->query("SELECT fechaCreado FROM datosestaciones where idEstacion = " .$id);
            $temps = [];
            $humedad = [];
            $presion = [];
            $velocidadViento = [];
            $direccionViento = [];
            $radiacionSolar = [];
            $radiacionIndiceUV = [];
            $indicePluviometrico = [];
            $fechasDelMesSeparadas = [];
            $fechas = [];
            $fechas_del_mes = [];

            while ($row = $result->fetch_assoc()) {
                array_push($fechas, $row['fechaCreado']);
            }
            foreach ($fechas as $individual) {
                if(date("m") == date("m", strtotime($individual))){
                    array_push($fechas_del_mes, $individual);
                }
            }

            for($i = 0; $i < count($fechas_del_mes); $i++) {
                $fechaSeparadaMes = explode(" ", $fechas_del_mes[$i]);
                array_push($fechasDelMesSeparadas, $fechaSeparadaMes[0]);
            }
            
            $fechasDelMesFinales = array_unique($fechasDelMesSeparadas);
            
            foreach($fechasDelMesFinales as $fecha) {
                $consultaPorCadaDia = $conn->query("SELECT * FROM datosestaciones where fechaCreado LIKE '". $fecha ."%' and idEstacion = " .$id.";");
                while ($row = $consultaPorCadaDia->fetch_assoc()) {
                  array_push($temps, $row['temperatura']);
                  array_push($humedad, $row['humedad']);
                  array_push($presion, $row['presion']);
                  array_push($velocidadViento, $row['velocidadViento']);
                  array_push($direccionViento, $row['direccionViento']);
                  array_push($radiacionSolar, $row['radiacionSolar']);
                  array_push($radiacionIndiceUV, $row['radiacionIndiceUV']);
                  array_push($indicePluviometrico, $row['indicePluviometrico']);
                }
                array_push($temps, "EXIT");
                array_push($humedad, "EXIT");
                array_push($presion, "EXIT");
                array_push($velocidadViento, "EXIT");
                array_push($direccionViento, "EXIT");
                array_push($radiacionSolar, "EXIT");
                array_push($radiacionIndiceUV, "EXIT");
                array_push($indicePluviometrico, "EXIT");
            }   
            
            $datosDividosPorDia = [];
            $arregloFinalTemp = [];
            $arregloFinalHume = [];
            $arregloFinalPres = [];
            $arregloFinalvelViento = [];
            $arregloFinalradSolar = [];
            $arregloFinalradSolarUV = [];
            $arregloFinalindPluvio = [];
            $promedio_en_dias_temp = [];
            $promedio_en_dias_hume = [];
            $promedio_en_dias_pres = [];
            $promedio_en_dias_velviento = [];
            $promedio_en_dias_radsolar = [];
            $promedio_en_dias_radsolaruv = [];
            $promedio_en_dias_indpluvio = [];
        
            foreach($temps as $temp) {
                if($temp != "EXIT") {
                    array_push($datosDividosPorDia, $temp);
                } else {
                    array_push($arregloFinalTemp, $datosDividosPorDia);
                    $datosDividosPorDia = [];
                }
            }
        
            foreach($humedad as $hume) {
                if($hume != "EXIT") {
                    array_push($datosDividosPorDia, $hume);
                } else {
                    array_push($arregloFinalHume, $datosDividosPorDia);
                    $datosDividosPorDia = [];
                }
            }
        
            foreach($presion as $pres) {
                if($pres != "EXIT") {
                    array_push($datosDividosPorDia, $pres);
                } else {
                    array_push($arregloFinalPres, $datosDividosPorDia);
                    $datosDividosPorDia = [];
                }
            }
        
            foreach($velocidadViento as $velViento) {
                if($velViento != "EXIT") {
                    array_push($datosDividosPorDia, $velViento);
                } else {
                    array_push($arregloFinalvelViento, $datosDividosPorDia);
                    $datosDividosPorDia = [];
                }
            }
        
            foreach($radiacionSolar as $radsolar) {
                if($radsolar != "EXIT") {
                    array_push($datosDividosPorDia, $radsolar);
                } else {
                    array_push($arregloFinalradSolar, $datosDividosPorDia);
                    $datosDividosPorDia = [];
                }
            }
        
            foreach($radiacionIndiceUV as $raduv) {
                if($raduv != "EXIT") {
                    array_push($datosDividosPorDia, $raduv);
                } else {
                    array_push($arregloFinalradSolarUV, $datosDividosPorDia);
                    $datosDividosPorDia = [];
                }
            }
        
            foreach($indicePluviometrico as $indpluvio) {
                if($indpluvio != "EXIT") {
                    array_push($datosDividosPorDia, $indpluvio);
                } else {
                    array_push($arregloFinalindPluvio, $datosDividosPorDia);
                    $datosDividosPorDia = [];
                }
            }
        
        
            for($i = 0; $i < count($arregloFinalTemp); $i++) {
                 array_push($promedio_en_dias_temp, array_sum($arregloFinalTemp[$i]) / count(array_filter($arregloFinalTemp[$i])));
            }
        
            for($i = 0; $i < count($arregloFinalHume); $i++) {
                array_push($promedio_en_dias_hume, array_sum($arregloFinalHume[$i]) / count(array_filter($arregloFinalHume[$i])));
            }
        
            for($i = 0; $i < count($arregloFinalPres); $i++) {
                array_push($promedio_en_dias_pres, array_sum($arregloFinalPres[$i]) / count(array_filter($arregloFinalPres[$i])));
            }
        
            for($i = 0; $i < count($arregloFinalvelViento); $i++) {
               array_push($promedio_en_dias_velviento, array_sum($arregloFinalvelViento[$i]) / count(array_filter($arregloFinalvelViento[$i])));
            }
        
            for($i = 0; $i < count($arregloFinalradSolar); $i++) {
            array_push($promedio_en_dias_radsolar, array_sum($arregloFinalradSolar[$i]) / count(array_filter($arregloFinalradSolar[$i])));
            }
        
            for($i = 0; $i < count($arregloFinalradSolarUV); $i++) {
            array_push($promedio_en_dias_radsolaruv, array_sum($arregloFinalradSolarUV[$i]) / count(($arregloFinalradSolarUV[$i])));
            }
        
            for($i = 0; $i < count($arregloFinalindPluvio); $i++) {
                array_push($promedio_en_dias_indpluvio, array_sum($arregloFinalindPluvio[$i]) / count(($arregloFinalindPluvio[$i])));
            }
        
        
            $datos = array('temperatura' => $promedio_en_dias_temp,
            'humedad' => $promedio_en_dias_hume,
            'presion' => $promedio_en_dias_pres,
            'velviento' => $promedio_en_dias_velviento,
            'radsolar' => $promedio_en_dias_radsolar,
            'radsolaruv' => $promedio_en_dias_radsolaruv,
            'indpluvio' => $promedio_en_dias_indpluvio);
        
            return $datos;
        }
// -------------------- PARSEAR FECHAS DE LA ESTACION POR MES ------------------------ //

// -------------------- PARSEAR FECHAS DE LA ESTACION POR AÑO ------------------------ //
        public function promedioYear($id) {
            $conn = OpenCon();
            $result = $conn->query("SELECT fechaCreado FROM datosestaciones where idEstacion = " .$id);
            $temps = [];
            $humedad = [];
            $presion = [];
            $velocidadViento = [];
            $direccionViento = [];
            $radiacionSolar = [];
            $radiacionIndiceUV = [];
            $indicePluviometrico = [];
            $fechasDelAnioSeparadas = [];
            $datosDividosPorMes = [];
            $arregloFinalAnio = [];
            $promedioPorMes = [];
            $fechas = [];
            $fechas_del_anio = [];

            while ($row = $result->fetch_assoc()) {
                array_push($fechas, $row['fechaCreado']);
            }
            foreach ($fechas as $individual) {
                if(date("Y") == date("Y", strtotime($individual))){
                    array_push($fechas_del_anio, $individual);
                }
            }

            for($i = 0; $i < count($fechas_del_anio); $i++) {
                $fechaSeparadaAnio = explode(" ", $fechas_del_anio[$i]);
                array_push($fechasDelAnioSeparadas, $fechaSeparadaAnio[0]);
            }
            
            $fechasDelAnioFinales = array_unique($fechasDelAnioSeparadas);
            
            foreach($fechasDelAnioFinales as $fecha) {
                $consultaPorCadaDia = $conn->query("SELECT * FROM datosestaciones where fechaCreado LIKE '". $fecha ."%' and idEstacion = " .$id.";");
                while ($row = $consultaPorCadaDia->fetch_assoc()) {
                    array_push($temps, $row['temperatura']);
                    array_push($humedad, $row['humedad']);
                    array_push($presion, $row['presion']);
                    array_push($velocidadViento, $row['velocidadViento']);
                    array_push($direccionViento, $row['direccionViento']);
                    array_push($radiacionSolar, $row['radiacionSolar']);
                    array_push($radiacionIndiceUV, $row['radiacionIndiceUV']);
                    array_push($indicePluviometrico, $row['indicePluviometrico']);
                  }
            $fecha1 = substr(current($fechasDelAnioFinales), 0, -3);
                $fecha2 = substr(next($fechasDelAnioFinales), 0, -3);
                if($fecha1 != $fecha2) {
                    array_push($temps, "EXIT");
                    array_push($humedad, "EXIT");
                    array_push($presion, "EXIT");
                    array_push($velocidadViento, "EXIT");
                    array_push($direccionViento, "EXIT");
                    array_push($radiacionSolar, "EXIT");
                    array_push($radiacionIndiceUV, "EXIT");
                    array_push($indicePluviometrico, "EXIT");
                }
            }  
            
            $arregloFinalTemp = [];
            $arregloFinalHume = [];
            $arregloFinalPres = [];
            $arregloFinalvelViento = [];
            $arregloFinalradSolar = [];
            $arregloFinalradSolarUV = [];
            $arregloFinalindPluvio = [];
            $promedio_en_mes_temp = [];
            $promedio_en_mes_hume = [];
            $promedio_en_mes_pres = [];
            $promedio_en_mes_velviento = [];
            $promedio_en_mes_radsolar = [];
            $promedio_en_mes_radsolaruv = [];
            $promedio_en_mes_indpluvio = [];
        
            foreach($temps as $temp) {
                if($temp != "EXIT") {
                    array_push($datosDividosPorMes, $temp);
                } else {
                    array_push($arregloFinalTemp, $datosDividosPorMes);
                    $datosDividosPorMes = [];
                }
            }
        
            foreach($humedad as $hume) {
                if($hume != "EXIT") {
                    array_push($datosDividosPorMes, $hume);
                } else {
                    array_push($arregloFinalHume, $datosDividosPorMes);
                    $datosDividosPorMes = [];
                }
            }
        
            foreach($presion as $pres) {
                if($pres != "EXIT") {
                    array_push($datosDividosPorMes, $pres);
                } else {
                    array_push($arregloFinalPres, $datosDividosPorMes);
                    $datosDividosPorMes = [];
                }
            }
        
            foreach($velocidadViento as $velViento) {
                if($velViento != "EXIT") {
                    array_push($datosDividosPorMes, $velViento);
                } else {
                    array_push($arregloFinalvelViento, $datosDividosPorMes);
                    $datosDividosPorMes = [];
                }
            }
        
            foreach($radiacionSolar as $radsolar) {
                if($radsolar != "EXIT") {
                    array_push($datosDividosPorMes, $radsolar);
                } else {
                    array_push($arregloFinalradSolar, $datosDividosPorMes);
                    $datosDividosPorMes = [];
                }
            }
        
            foreach($radiacionIndiceUV as $raduv) {
                if($raduv != "EXIT") {
                    array_push($datosDividosPorMes, $raduv);
                } else {
                    array_push($arregloFinalradSolarUV, $datosDividosPorMes);
                    $datosDividosPorMes = [];
                }
            }
        
            foreach($indicePluviometrico as $indpluvio) {
                if($indpluvio != "EXIT") {
                    array_push($datosDividosPorMes, $indpluvio);
                } else {
                    array_push($arregloFinalindPluvio, $datosDividosPorMes);
                    $datosDividosPorMes = [];
                }
            }
            
            for($i = 0; $i < count($arregloFinalTemp); $i++) {
                array_push($promedio_en_mes_temp, array_sum($arregloFinalTemp[$i]) / count(array_filter($arregloFinalTemp[$i])));
           }
        
           for($i = 0; $i < count($arregloFinalHume); $i++) {
               array_push($promedio_en_mes_hume, array_sum($arregloFinalHume[$i]) / count(array_filter($arregloFinalHume[$i])));
           }
        
           for($i = 0; $i < count($arregloFinalPres); $i++) {
               array_push($promedio_en_mes_pres, array_sum($arregloFinalPres[$i]) / count(array_filter($arregloFinalPres[$i])));
           }
        
           for($i = 0; $i < count($arregloFinalvelViento); $i++) {
              array_push($promedio_en_mes_velviento, array_sum($arregloFinalvelViento[$i]) / count(array_filter($arregloFinalvelViento[$i])));
           }
        
           for($i = 0; $i < count($arregloFinalradSolar); $i++) {
           array_push($promedio_en_mes_radsolar, array_sum($arregloFinalradSolar[$i]) / count(array_filter($arregloFinalradSolar[$i])));
           }
        
           for($i = 0; $i < count($arregloFinalradSolarUV); $i++) {
           array_push($promedio_en_mes_radsolaruv, array_sum($arregloFinalradSolarUV[$i]) / count(($arregloFinalradSolarUV[$i])));
           }
        
           for($i = 0; $i < count($arregloFinalindPluvio); $i++) {
               array_push($promedio_en_mes_indpluvio, array_sum($arregloFinalindPluvio[$i]) / count(($arregloFinalindPluvio[$i])));
           }
            
           $datos = array('temperatura' => $promedio_en_mes_temp,
            'humedad' => $promedio_en_mes_hume,
            'presion' => $promedio_en_mes_pres,
            'velviento' => $promedio_en_mes_velviento,
            'radsolar' => $promedio_en_mes_radsolar,
            'radsolaruv' => $promedio_en_mes_radsolaruv,
            'indpluvio' => $promedio_en_mes_indpluvio);
        
            return $datos;
        }

// -------------------- PARSEAR FECHAS DE LA ESTACION POR AÑO ------------------------ //

        public function verDatosDeEstacionPorFecha($tipoFecha, $id) {
            try {
                // Se conecta a la base de datos.
                $fecha = "";
                $db = new Conexion();
                if($tipoFecha == "Dia") {
                    $fecha = date("Y") . "-" . date("m") . "-" . date("d");
                } else if($tipoFecha == "Mes") {
                    $fecha = date("Y") . "-" . date("m");
                } else if($tipoFecha == "Semanal") {

                } else if($tipoFecha == "Anual") {
                    $fecha = date("Y");
                }
                if($tipoFecha == "Semanal") {
                    $sql = "SELECT * FROM datosestaciones where YEARWEEK(fechaCreado, 1) = YEARWEEK(CURDATE(), 1) AND idEstacion=:id;";  
                } else {
                    $sql = "SELECT * FROM datosEstaciones WHERE fechaCreado like '" . $fecha . "%' AND idEstacion=:id";  
                }
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

        public function verPromediosPorTipoFecha($tipoFecha, $id) {
            try {
                // Se conecta a la base de datos.
                $promedio = [];
                $db = new Conexion();
                if($tipoFecha == "Dia") {
                    $promedio = $this->promedioDia($id);
                } else if($tipoFecha == "Mes") {
                    $promedio = $this->promedioMes($id);
                } else if($tipoFecha == "Semanal") {
                    $promedio = $this->promedioSemana($id); 
                } else if($tipoFecha == "Anual") {
                    $promedio = $this->promedioYear($id);
                }
                // if($tipoFecha == "Semanal") {
                //     $sql = "SELECT * FROM datosestaciones where YEARWEEK(fechaCreado, 1) = YEARWEEK(CURDATE(), 1) AND idEstacion=:id;";  
                // } else {
                //     $sql = "SELECT * FROM datosEstaciones WHERE fechaCreado like '" . $fecha . "%' AND idEstacion=:id";  
                // }
                // $statement = $db->prepare($sql);
                // $statement->bindValue(':id', $id);
                // $statement->execute();
                if ($promedio != null) {;
                    return $promedio;
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
    }
?>
