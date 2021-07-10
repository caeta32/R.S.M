<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UsuarioController extends Controller
{
    public function RegistroUsuario(Request $request)
    {
        $url = 'http://localhost/server-central_JEE/ws/';

        $usuario = new Usuario;
        $usuario->email = $request->email;
        $usuario->contrasenia = $request->pass;
        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->nivelAcceso = $request->nivelacceso;
        $passconf = $request->passconf;
        $pass = password_hash($usuario->contrasenia, PASSWORD_DEFAULT);


        $fields = [
            'email'=>$usuario->email,
            'nombre'=>$usuario->nombre,
            'apellido'=>$usuario->apellido,
            'nivelAcceso'=>$usuario->nivelAcceso,
            'contrasenia'=>$pass,
            'accion'=>'nuevoUsuario'
        ];

        $fields_string = http_build_query($fields);

        if ($usuario->contrasenia == $passconf) {
            $ch = curl_init();

            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, true);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            if (str_contains($result, '409')) {
                return redirect('/registererroremail');
            } else {
                return redirect('/login');
            }
        } else {
            return redirect('/registererrorpass');
        }
    }

    public function LoginUsuario(Request $request)
    {
        $url = 'http://localhost/server-central_JEE/ws/';

        $email = $request->email;
        $pass = $request->pass;

        $urlCompleta = $url . '?' . "accion=verUsuario&email=" . $email;

        $curl_connection = curl_init($urlCompleta);
        curl_setopt($curl_connection,CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl_connection);

        if (str_contains($result, '404')) {
            return redirect('/loginerror');
        } else {
            $datosUsuarioSeparados = explode('"', $result);
            $passHashedBad = $datosUsuarioSeparados[9];
            $passHashedGood = str_replace("\\", '', $passHashedBad);
            $nombre = $datosUsuarioSeparados[13];

            if(password_verify($pass, $passHashedGood)) {
                Session::put('username', $nombre);
                return redirect('/iniciousuariogratis');
            } else {
                return redirect('/loginerror');
            }
        }
//        $usuario = Cliente::where(['email' => $request->email])->first();
//        // Chequea si el usuario existe, su password y si esta confirmado.
//        if (!$usuario || !Hash::check($request->pass, $usuario->pass) || $usuario->confirmado == 0) {
//            return redirect('/loginerror');
//        } else {
//            if ($usuario->email == "administradores@tutienda.com") {
//                $request->session()->put('usuario', $usuario);
//                return redirect('/administradores');
//            } else {
//                $request->session()->put('usuario', $usuario);
//                return redirect('/principal');
//            }
//        }
    }
}
