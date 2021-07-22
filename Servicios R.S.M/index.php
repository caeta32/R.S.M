<?php
    include './CustomConstants.php';
    include './Utils.php';
    include './WebService.php';

// Acciones iniciales --------------------------------------------------------------------------

    // Se crea el array que contendrá la respuesta.
    $response = array(
        'result' => '',
        'status' => '',
        'warning' => '',
        'error' => ''
    );
// ========================================================================================================
// ========================================================================================================
    // MÉTODO GET
    // Para consultar datos.
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Notificar todos los errores excepto E_NOTICE
        error_reporting(E_ALL ^ E_NOTICE);
        // Se crea un objeto de la clase que provee los servicios.
        $ws = new WebService;

        try {
            if(isset($_GET['accion']) && ($_GET['accion'] !== "")) {

                switch($_GET['accion']) {

                    case 'verTodoDatosEstaciones' :
                        $response['result'] = $ws->verTodos_DatosEstaciones();
                        $response['status'] = 200;
                        header('HTTP/1.1 200 OK');
                        break;
                //------------------------------------------------
                    case 'verTodoUsuarios' :
                        $response['result'] = $ws->verTodos_Usuarios();
                        $response['status'] = 200;
                        header('HTTP/1.1 200 OK');
                        break;
                //------------------------------------------------
                    case 'verTodoEstaciones' :
                        $response['result'] = $ws->verTodos_Estaciones();
                        $response['status'] = 200;
                        header('HTTP/1.1 200 OK');
                        break;
                //------------------------------------------------
                    case 'verDatosDeEstacion' :
                        if(isset($_GET['id']) && ($_GET['id'] !== "")) {
                            $response['result'] = $ws->verDatosDeEstacion($_GET['id']);
                            $response['status'] = 200;
                            header('HTTP/1.1 200 OK');
                        } else {
                            $response['error'] = 'id no recibido';
                            $response['status'] = 400;
                            header('HTTP/1.1 400 Bad Request');
                        }
                        break;
                //------------------------------------------------
                    case 'verUsuario' :
                        // Se obtiene el id para saber qué usuario devolver.
                        if(isset($_GET['email']) && ($_GET['email'] !== ""))
                            $id = $_GET['email'];
                        elseif(isset($_GET['idReducido']))
                            $id = $_GET['idReducido'];
                        else {
                            // En caso de que no se encuentre ni email ni idReducido,
                            // no se puede saber qué usuario devolver.
                            $response['error'] = 'Email o id no recibidos';
                            $response['status'] = 400;
                            header('HTTP/1.1 400 Bad Request');
                            break;
                        }
                        $response['result'] = $ws->verUsuario($id);
                        $response['status'] = 200;
                        header('HTTP/1.1 200 OK');
                        break;
                //------------------------------------------------
                    case 'verEstacion' :
                        if(isset($_GET['id']) && ($_GET['id'] !== "")) {
                            $response['result'] = $ws->verEstacion($_GET['id']);
                            $response['status'] = 200;
                            header('HTTP/1.1 200 OK');
                        } else {
                            $response['error'] = 'id no recibido';
                            $response['status'] = 400;
                            header('HTTP/1.1 400 Bad Request');
                        }
                        break;
                //------------------------------------------------
                    case 'obtenerEstacionMasCercana' :
                        $response['result'] = $ws->estacionMasCercana($_GET['id'], $_GET['latitud'], $_GET['longitud']);
                        $response['status'] = 200;
                        header('HTTP/1.1 200 OK');
                        break;
                //------------------------------------------------
                    default :
                        $response['error'] = 'Acción incorrecta.';
                        $response['status'] = 422;
                        header('HTTP/1.1 422 Unprocessable Entity');
                }

            }  else {
                $response['error'] = 'Acción sin especificar.';
                $response['status'] = 400;
                header('HTTP/1.1 400 Bad Request');
            }
            // Se agrega el tipo del contenido de la respuesta a la cabecera.
            header('Content-type: application/json');
            // Se devuelve la respuesta.
            echo json_encode($response);
            // Notificar todos los errores de PHP 
            error_reporting(E_ALL);
            exit;
        } catch (PDOException $e) {
            $response['error'] = 'Error: ' . $e->getMessage();
            $response['status'] = 500;
            header('HTTP/1.1 500 Internal Server Error');
            // Se agrega el tipo del contenido de la respuesta a la cabecera.
            header('Content-type: application/json');
            // Se devuelve la respuesta.
            echo json_encode($response);
            // Notificar todos los errores de PHP 
            error_reporting(E_ALL);
            exit;
        } catch (Exception $e) {
            $message = explode(':', $e->getMessage());

            if (count($message) > 1) {
                header('HTTP/1.1 '.$message[0]);
                $response['status'] = intval($message[0]);
                $response['error'] = $message[1];
            } else {
                header('HTTP/1.1 500 Internal Server Error');
                $response['status'] = 500;
                $response['error'] = $e->getMessage();
            }
            // Se agrega el tipo del contenido de la respuesta a la cabecera.
            header('Content-type: application/json');
            // Se devuelve la respuesta.
            echo json_encode($response);
            // Notificar todos los errores de PHP 
            error_reporting(E_ALL);
            exit;
        }
    }
// ========================================================================================================
// ========================================================================================================
    // MÉTODO POST
    // Para ingresar datos.
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Notificar todos los errores excepto E_NOTICE
        error_reporting(E_ALL ^ E_NOTICE);
        // Se crea un objeto de la clase que provee los servicios.
        $ws = new WebService;

        try {
            if(isset($_POST['accion']) && ($_GET['accion'] !== "")) {

                switch ($_POST['accion']) {
                // ---------------------------------------------------------
                    case 'nuevaInfoEstacion' :
                        if (isset($_POST['idEstacion']) && ($_POST['idEstacion'] !== "")) {
                            // Se insertan los registros recibiendo los parámetros.
                            // En caso de no recibir uno de los parámetros, ingresará el dato de todos modos, 
                            // aunque en el cuerpo de la respuesta se devolverá una advertencia, a excepción del idEstacion.
                            $response['result'] = $ws->insertarRegistrosMetereologicos($_POST['temperatura'], $_POST['humedad'], $_POST['presion'], $_POST['velocidadViento'], $_POST['direccionViento'], $_POST['radiacionSolar'], $_POST['radiacionIndiceUV'], $_POST['indicePluviometrico'], $_POST['idEstacion']);

                            if ($undefinedParams = Utils::undefinedParams_datosEstacion($_POST['temperatura'], $_POST['humedad'], $_POST['presion'], $_POST['velocidadViento'], $_POST['direccionViento'], $_POST['radiacionSolar'], $_POST['radiacionIndiceUV'], $_POST['indicePluviometrico'])) {
                                $response['warning'] = 'Parametros indefinidos: ' . implode(', ', $undefinedParams);
                            }
                            $response['status'] = 200;
                            header('HTTP/1.1 200 OK');
                        }  else {
                            $response['error'] = 'idEstacion no recibido';
                            $response['status'] = 400;
                            header('HTTP/1.1 400 Bad Request');
                        }
                        break;
                // ---------------------------------------------------------
                    case 'nuevoUsuario' :
                        if (isset($_POST['email']) && ($_POST['email'] !== "") && isset($_POST['contrasenia']) && ($_POST['contrasenia'] !== "") && isset($_POST['nivelAcceso']) && ($_POST['nivelAcceso'] !== "")) {
                            if (($_POST['nivelAcceso'] === 'a') || ($_POST['nivelAcceso'] === 'b') || ($_POST['nivelAcceso'] === 'c')) {
                                // Se insertan los registros recibiendo los parámetros.
                                // En caso de no recibir uno de los parámetros, ingresará el dato de todos modos, 
                                // aunque en el cuerpo de la respuesta se devolverá una advertencia, a excepción del email, contraseña y nivelAcceso.
                                $response['result'] = $ws->ingresarUsuario($_POST['email'], $_POST['nombre'], $_POST['contrasenia'], $_POST['idReducido'], $_POST['nivelAcceso']);

                                if ($undefinedParams = Utils::undefinedParams_usuarios($_POST['idReducido'], $_POST['nombre'])) {
                                    $response['warning'] = 'Parametros indefinidos: ' . implode(', ', $undefinedParams);
                                }
                                $response['status'] = 200;
                                header('HTTP/1.1 200 OK');
                            } else {
                                $response['error'] = "Error: nivelAcceso debe ser 'a', 'b' o 'c'";
                                $response['status'] = 422;
                                header('HTTP/1.1 422 Unprocessable Entity');
                            }
                        } else {
                            $response['error'] = 'Email, contraseña o nivelAcceso no recibidos';
                            $response['status'] = 400;
                            header('HTTP/1.1 400 Bad Request');
                        }
                        break;
                // ---------------------------------------------------------
                    case 'nuevaEstacion' :
                        if (isset($_POST['nombre']) && ($_POST['nombre'] !== "") && isset($_POST['localidad']) && ($_POST['localidad'] !== "")) {
                            // Se insertan los registros recibiendo los parámetros.
                            $response['result'] = $ws->ingresarEstacion($_POST['id'], $_POST['nombre'], $_POST['localidad']);

                            if ($undefinedParams = Utils::undefinedParams_estaciones($_POST['nombre'], $_POST['localidad'], $_POST['longitud'], $_POST['latitud'])) {
                                $response['warning'] = 'Parametros indefinidos: ' . implode(', ', $undefinedParams);
                            }
                            $response['status'] = 200;
                            header('HTTP/1.1 200 OK');
                        } else {
                            $response['error'] = 'nombre o localidad no recibidos';
                            $response['status'] = 400;
                            header('HTTP/1.1 400 Bad Request');
                        }
                        break;
                // ---------------------------------------------------------
                    default :
                        $response['error'] =  'Accion incorrecta';
                        $response['status'] = 422;
                        header('HTTP/1.1 422 Unprocessable Entity');
                }

            } else {
                $response['error'] =  'Accion sin especificar';
                $response['status'] = 400;
                header('HTTP/1.1 400 Bad Request');
            }
            // Se agrega el tipo del contenido de la respuesta a la cabecera.
            header('Content-type: application/json');
            echo json_encode($response);
            // Notificar todos los errores de PHP 
            error_reporting(E_ALL);
            exit;
        } catch (PDOException $e) {
            $response['error'] =  'Error: ' . $e->getMessage();
            $response['status'] = 500;
            header('HTTP/1.1 500 Internal Server Error');
            // Se agrega el tipo del contenido de la respuesta a la cabecera.
            header('Content-type: application/json');
            echo json_encode($response);
            // Notificar todos los errores de PHP 
            error_reporting(E_ALL);
            exit;
        } catch (Exception $e) {
            $message = explode(':', $e->getMessage());

            if (count($message) > 1) {
                header('HTTP/1.1 '.$message[0]);
                $response['status'] = intval($message[0]);
                $response['error'] = $message[1];
            } else {
                header('HTTP/1.1 500 Internal Server Error');
                $response['status'] = 500;
                $response['error'] = $e->getMessage();
            }
            // Se agrega el tipo del contenido de la respuesta a la cabecera.
            header('Content-type: application/json');
            // Se devuelve la respuesta.
            echo json_encode($response);
            // Notificar todos los errores de PHP 
            error_reporting(E_ALL);
            exit;
        }
    }
// ========================================================================================================
// ========================================================================================================
    // MÉTODO PUT
    // Para actualizar datos.
    if($_SERVER['REQUEST_METHOD'] == 'PUT') {
        // Notificar todos los errores excepto E_NOTICE
        error_reporting(E_ALL ^ E_NOTICE);
        // Se crea un objeto de la clase que provee los servicios.
        $ws = new WebService;
        try {
            if(isset($_GET['accion']) && ($_GET['accion'] !== "")) {

                switch ($_GET['accion']) {
                // ---------------------------------------------------------
                    case 'actualizarEstacion' :
                        if(isset($_GET['id']) && ($_GET['id'] !== "")) {
                            $response['result'] = $ws->actualizarEstacion($_GET['id'], $_GET['nombre'], $_GET['localidad'], $_GET['longitud'], $_GET['latitud']);
                            $response['status'] = 200;
                            header('HTTP/1.1 200 OK');
                        } else {
                            $response['error'] = 'id no recibido';
                            $response['status'] = 400;
                            header('HTTP/1.1 400 Bad Request');
                        }
                        break;
                // ---------------------------------------------------------
                    case 'actualizarUsuario' :
                        // Se obtiene el id para saber qué usuario actualizar.
                        if(isset($_GET['email']) && ($_GET['email'] !== ""))
                            $id = $_GET['email'];
                        elseif(isset($_GET['idReducido']) && ($_GET['idReducido'] !== ""))
                            $id = $_GET['idReducido'];
                        else {
                            // En caso de que no se encuentre ni email ni idReducido,
                            // no se puede saber qué usuario actualizar.
                            $response['error'] = 'Email o id no recibidos';
                            $response['status'] = 400;
                            header('HTTP/1.1 400 Bad Request');
                            break;
                        }

                        $response['result'] =  $ws->actualizarDatosUsuario($id, $_GET['nombre'], $_GET['contrasenia'], $_GET['nivelAcceso']);
                        $response['status'] = 200;
                        header('HTTP/1.1 200 OK');
                        break;
                // ---------------------------------------------------------
                    default :
                        $response['error'] =  'Accion incorrecta';
                        $response['status'] = 422;
                        header('HTTP/1.1 422 Unprocessable Entity');
                }
            } else {
                $response['error'] =  'Accion sin especificar.';
                $response['status'] = 400;
                header('HTTP/1.1 400 Bad Request');
            }
            // Se agrega el tipo del contenido de la respuesta a la cabecera.
            header('Content-type: application/json');
            // Se envía la respuesta.
            echo json_encode($response);
            // Notificar todos los errores de PHP 
            error_reporting(E_ALL);
            exit;

        } catch (PDOException $e) {
            $response['error'] = 'Error: ' . $e->getMessage();
            $response['status'] = 500;
            header('HTTP/1.1 500 Internal Server Error');
            // Se agrega el tipo del contenido de la respuesta a la cabecera.
            header('Content-type: application/json');
            echo json_encode($response);
            // Notificar todos los errores de PHP 
            error_reporting(E_ALL);
            exit;
        } catch (Exception $e) {
            $message = explode(':', $e->getMessage());

            if (count($message) > 1) {
                header('HTTP/1.1 '.$message[0]);
                $response['status'] = intval($message[0]);
                $response['error'] = $message[1];
            } else {
                header('HTTP/1.1 500 Internal Server Error');
                $response['status'] = 500;
                $response['error'] = $e->getMessage();
            }
            // Se agrega el tipo del contenido de la respuesta a la cabecera.
            header('Content-type: application/json');
            echo json_encode($response);
            // Notificar todos los errores de PHP 
            error_reporting(E_ALL);
            exit;
        }
    }
// ========================================================================================================
// ========================================================================================================
    // MÉTODO DELETE
    if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        // Notificar todos los errores excepto E_NOTICE
        error_reporting(E_ALL ^ E_NOTICE);
        // Se crea un objeto de la clase que provee los servicios.
        $ws = new WebService;
        try {
            if(isset($_GET['accion']) && ($_GET['accion'] !== "")) {

                switch ($_GET['accion']) {
                // ---------------------------------------------------------
                    case 'eliminarEstacion' :
                        if(isset($_GET['id']) && ($_GET['id'] !== "")) {
                            $response['result'] = $ws->eliminarEstacion($_GET['id']);
                            $response['status'] = 200;
                            header('HTTP/1.1 200 OK');
                        } else {
                            $response['error'] = 'id no recibido';
                            $response['status'] = 400;
                            header('HTTP/1.1 400 Bad Request');
                        }
                        break;
                // ---------------------------------------------------------
                    case 'eliminarUsuario' :
                        if(isset($_GET['email']) && ($_GET['email'] !== "")) {
                            $response['result'] = $ws->eliminarUsuario($_GET['email']);
                            $response['status'] = 200;
                            header('HTTP/1.1 200 OK');
                        } else {
                            $response['error'] = 'Email no recibido';
                            $response['status'] = 400;
                            header('HTTP/1.1 400 Bad Request');
                        }
                        break;
                // ---------------------------------------------------------
                    case 'eliminarDatosDeEstacion' :
                        if(isset($_GET['id']) && ($_GET['id'] !== "")) {
                            $response['result'] = $ws->eliminarDatosDeEstacion($_GET['id']);
                            $response['status'] = 200;
                            header('HTTP/1.1 200 OK');
                        } else {
                            $response['error'] = 'id no recibido';
                            $response['status'] = 400;
                            header('HTTP/1.1 400 Bad Request');
                        }
                        break;
                // ---------------------------------------------------------
                    default :
                        $response['error'] =  'Accion incorrecta';
                        $response['status'] = 422;
                        header('HTTP/1.1 422 Unprocessable Entity');
                }
            } else {
                $response['error'] =  'Accion sin especificar.';
                $response['status'] = 400;
                header('HTTP/1.1 400 Bad Request');
            }
            // Se agrega el tipo del contenido de la respuesta a la cabecera.
            header('Content-type: application/json');
            // Se envía la respuesta.
            echo json_encode($response);
            // Notificar todos los errores de PHP 
            error_reporting(E_ALL);
            exit;

        } catch (PDOException $e) {
            $response['error'] = 'Error: ' . $e->getMessage();
            $response['status'] = 500;
            header('HTTP/1.1 500 Internal Server Error');
            // Se agrega el tipo del contenido de la respuesta a la cabecera.
            header('Content-type: application/json');
            echo json_encode($response);
            // Notificar todos los errores de PHP 
            error_reporting(E_ALL);
            exit;
        } catch (Exception $e) {
            $message = explode(':', $e->getMessage());

            if (count($message) > 1) {
                header('HTTP/1.1 '.$message[0]);
                $response['status'] = intval($message[0]);
                $response['error'] = $message[1];
            } else {
                header('HTTP/1.1 500 Internal Server Error');
                $response['status'] = 500;
                $response['error'] = $e->getMessage();
            }
            // Se agrega el tipo del contenido de la respuesta a la cabecera.
            header('Content-type: application/json');
            echo json_encode($response);
            // Notificar todos los errores de PHP 
            error_reporting(E_ALL);
            exit;
        }
    }
// ========================================================================================================
// ========================================================================================================
    //MÉTODO ERRÓNEO
    header('HTTP/1.1 400 Bad Request');
    $response['status'] = 400;
    $response['error'] = 'Metodo erroneo';
    // Se agrega el tipo del contenido de la respuesta a la cabecera.
    header('Content-type: application/json');
    echo json_encode($response);
    exit;
?>