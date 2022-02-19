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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('productos', 'ProductosController');

Route::post('/carrito/agregar', 'CarritoController@agregarProducto')->name('carrito/agregar');
Route::get('/carrito/resumen', 'CarritoController@resumenCarrito')->name('carrito/resumen');
Route::delete('/carrito/eliminar', 'CarritoController@eliminarProducto')->name('carrito/eliminar');