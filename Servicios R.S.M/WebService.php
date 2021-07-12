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
                    // En caso de recibir el id vacío, se le asigna null para que
                    // se auto-incremente por el gestor de la BD.
                    if ($id === "")
                        $id = null;
                    
                    // CONTROL
                    if ($nombre === "" || $localidad === "") {
                        $message = array();
                        $message[] = '422 Unprocessable Entity';
                        $message[] = 'Error: nombre y localidad no pueden ser cadenas vacias';
                        throw new Exception(implode(':', $message));
                    }
                        
                    // Se almacena la sentencia.
                    $sql = 'INSERT INTO estaciones (id, nombre, localidad) VALUES (:id, :nombre, :localidad)';
                    // Se prepara la sentencia.
                    $statement = $db->prepare($sql);
                    // Se pasan los parámetros a la sentencia.
                    $statement->bindValue(':id', $id);
                    $statement->bindValue(':nombre', $nombre);
                    $statement->bindValue(':localidad', $localidad);
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
        // $id puede ser el email o identificador único del usuario.
        // Se asume que $id está definido ya que es el único parámetro realmente necesario para
        // poder realizar la actualización.
        // Los parámetros que no lleguen definidos, se asumirá que no se quieren actualizar,
        // por lo que se mantendrá el valor que estaba registrado previamente, a excepción del id.
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
                        // Si ya se agregó uno antes, ingresa una coma.
                        if($quantityDefinedParams > 0)
                            $sql .= ', ';
                        $quantityDefinedParams += 1;
                        $sql .= 'nombre=:nombre';
                    }
                    if(isset($contrasenia)) {
                        // Si ya se agregó uno antes, ingresa una coma.
                        if($quantityDefinedParams > 0)
                            $sql .= ', ';
                        $quantityDefinedParams += 1;
                        $sql .= 'contrasenia=:contrasenia';
                    }
                    if(isset($nivelAcceso)) {
                        // Si ya se agregó uno antes, ingresa una coma.
                        if($quantityDefinedParams > 0)
                            $sql .= ', ';
                        $quantityDefinedParams += 1;
                        $sql .= 'nivelAcceso=:nivelAcceso';
                    }
                    // Si habían parámetros para actualizar definidos, entonces
                    // hay datos para actualizar, por lo que se ejecuta la sentencia.
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
        // Se asume que $id está definido ya que es el único parámetro realmente necesario para
        // poder realizar la actualización.
        // Los parámetros que no lleguen definidos, se asumirá que no se quieren actualizar,
        // por lo que se mantendrá el valor que estaba registrado previamente, a excepción del id.
        public function actualizarEstacion($id, $nombre, $localidad) {
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
                    $quantityDefinedParams = 0;
                    if(isset($nombre)) {
                        // Si ya se agregó uno antes, ingresa una coma.
                        if($quantityDefinedParams > 0)
                            $sql .= ', ';
                        $quantityDefinedParams += 1;
                        $sql .= 'nombre=:nombre';
                    }
                    if(isset($localidad)) {
                        // Si ya se agregó uno antes, ingresa una coma.
                        if($quantityDefinedParams > 0)
                            $sql .= ', ';
                        $quantityDefinedParams += 1;
                        $sql .= 'localidad=:localidad';
                    }
                    // Si habían parámetros para actualizar definidos, entonces
                    // hay datos para actualizar, por lo que se ejecuta la sentencia.
                    if($quantityDefinedParams > 0) {
                        $sql .= ' WHERE id=:id';
                        // Se prepara la sentencia.
                        $statement = $db->prepare($sql);
                        // Se pasan los parámetros a la sentencia.
                        $statement->bindValue(':id', $id);
                        
                        if(isset($nombre)) 
                            $statement->bindValue(':nombre', $nombre);
                        if(isset($localidad)) 
                            $statement->bindValue(':localidad', $localidad);
                        

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
        // Se asume que $id está definido.
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
        // Se asume que $email está definido.
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
        // Se asume que $id está definido.
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

    }
?>