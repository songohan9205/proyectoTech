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

//Ruta cuando no han generado Token y es obligatorio
Route::get('/login', function () {
    return response()->json(['Estado' => false, 'Respuesta' => 'Acceso no válido, debe autenticarse']);
})->name('login');

//Rutas con token Obligatorio
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/usuarios/login', 'AuthController@login')->name('usuarios/login');
    Route::post('/usuarios/registro', 'AuthController@registrarUsuario')->name('usuarios/registro');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        //Productos
        //Route::resource('productos', 'ProductosController');
        Route::get('/productos', 'ProductosController@index')->name('productos');
        Route::post('/productos/nuevo', 'ProductosController@nuevo')->name('productos/nuevo');
        Route::get('/productos/buscar/{id}', 'ProductosController@buscar')->name('productos/buscar');
        Route::put('/productos/actualizar/{id}', 'ProductosController@actualizar')->name('productos/actualizar');
        Route::delete('/productos/eliminar/{id}', 'ProductosController@eliminar')->name('productos/eliminar');
        Route::post('/productos/carga', 'ProductosController@cargaMasiva')->name('productos/carga');

        //Carrito de compras
        Route::post('/carrito/agregar', 'CarritoController@agregarProducto')->name('carrito/agregar');
        Route::get('/carrito/resumen', 'CarritoController@resumenCarrito')->name('carrito/resumen');
        Route::get('/carrito/comprar', 'CarritoController@compraProductos')->name('carrito/comprar');
        Route::delete('/carrito/eliminar', 'CarritoController@eliminarProducto')->name('carrito/eliminar');

        //Usuarios
        Route::get('/usuarios/finalizar', 'AuthController@cerrarSesion')->name('usuarios/finalizar');
        Route::get('/usuarios/info', 'AuthController@infoUsuario')->name('usuarios/info');

        //Reporte ventas
        Route::post('/reporte/ventas', 'ReporteController@reporteVentas')->name('/reporte/ventas');
    });
});
