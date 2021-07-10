<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Session;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('inicio');
});

Route::get('/navbartest', function () {
    return view('Navbars.navbarinvitado');
});

Route::get('/iniciousuariogratis', function () {
    return view('iniciousuariogratis');
});

Route::get('/estaciones', function () {
    return view('Estaciones.estaciones');
});

Route::get('/mapa', function () {
    return view('Mapa.mapa');
});

Route::get('/registro', function () {
    return view('Usuarios.registro');
});

Route::get('/registererrorpass', function () {
    return view('Usuarios.registererrorpass');
});

Route::get('/registererroremail', function () {
    return view('Usuarios.registererroremail');
});

Route::get('/login', function () {
    return view('Usuarios.login');
});

Route::get('/logout', function () {
    Session::forget('username');
    return redirect('/');
});

Route::get('/loginerror', function () {
    return view('Usuarios.loginerror');
});

Route::get('/altausuario', function () {
    return view('Usuarios.altausuario');
});

Route::get('/formaparte', function () {
    return view('FormaParte-FAQ.formaparte');
});

Route::get('/faq', function () {
    return view('FormaParte-FAQ.preguntasfrecuentes');
});

Route::post('/registrar', [UsuarioController::class, 'RegistroUsuario'])->name('registrarController');
Route::post('/loginusuario', [UsuarioController::class, 'LoginUsuario'])->name('loginController');


