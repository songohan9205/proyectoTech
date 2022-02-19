<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductosModel;
use Cart;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para todo lo relacionado con el carrito de compras
 * @author Johan Morales
 * @return void
 */
class CarritoController extends Controller
{

    /**
     * Función para el registro de un nuevo producto
     * @author Johan Morales     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function agregarProducto(Request $request)
    {

        try {
            $validarProducto = $this->validarProducto($request->id, $request->cantidad);
            $validarProducto = (array) $validarProducto->getData();

            if ($validarProducto['Estado']) {

                $userId = 1;
                Cart::session($userId)->add(array(
                    'id'    => $request->id,
                    'name'  => $validarProducto['Data']->nombre,
                    'price' => $validarProducto['Data']->precio,
                    'quantity' => $request->cantidad,
                    'attributes' => array()
                ));
                return response()->json(['Estado' => true, 'Respuesta' => 'Producto agregado al carrito', 'Data' => Cart::session($userId)->getContent()], 200);
            }

            return $validarProducto;
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }

    /**
     * Función para ver el resumen de compra del carrito
     * @author Johan Morales     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function resumenCarrito(Request $request)
    {
        try {
            $userId = 1;
            return response()->json(['Estado' => true, 'Respuesta' => 'Producto agregado al carrito', 'Data' => Cart::session($userId)->getContent()], 200);
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }

    /**
     * Función para eliminar un producto del carrito
     * @author Johan Morales     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function eliminarProducto(Request $request) {
        try {
            $userId = 1;
            Cart::session($userId)->remove($request->id);
            return response()->json(['Estado' => true, 'Respuesta' => 'Producto eliminado del carrito', 'Data' => Cart::session($userId)->getContent()], 200);
        }
        catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }


    /**
     * Función para validar que el producto exista y que tenga la cantidad solicitada
     * @author Johan Morales     
     * @param  bigInteger $id (Id del producto)
     * @param  integer $cantidad (Cantidad agregada al carrito)
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    private function validarProducto($id, $cantidad)
    {
        try {
            $producto = ProductosModel::find($id);
            if (empty($producto)) {
                return response()->json(['Estado' => false, 'Respuesta' => 'No existe un producto con Id: ' . $id, 'Data' => []], 200);
            }

            if ($producto->unidades < $cantidad) {
                return response()->json(['Estado' => false, 'Respuesta' => 'La cantidad solicitada excede el inventario del producto: ' . $producto->nombre, 'Data' => $producto], 200);
            }

            return response()->json(['Estado' => true, 'Respuesta' => 'El producto se puede agregar al carrito: ', 'Data' => $producto], 200);
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }
}
