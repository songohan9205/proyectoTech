<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Ruta cuando no han generado Token
Route::get('/login', function () {
    return response()->json(['Estado' => false, 'Respuesta' => 'Acceso no válido, debe autenticarse']);
})->name('login');

//Productos
Route::resource('productos', 'ProductosController');

//Carrito de compras
Route::post('/carrito/agregar', 'CarritoController@agregarProducto')->name('carrito/agregar');
Route::get('/carrito/resumen', 'CarritoController@resumenCarrito')->name('carrito/resumen');
Route::delete('/carrito/eliminar', 'CarritoController@eliminarProducto')->name('carrito/eliminar');

//Autenticación de usuarios
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/usuarios/login', 'AuthController@login');
    Route::post('/usuarios/registro', 'AuthController@registrarUsuario');

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@cerrarSesion');
        Route::get('user', 'AuthController@infoUsuario');
    });
});