<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;
use App\ProductosModel;
use App\CompraModel;
use App\DetalleCompraModel;
use Cart;

/**
 * Controlador para todo lo relacionado con el carrito de compras
 * @author Johan Morales
 * @return void
 */
class CarritoController extends Controller
{

    /**
     * @OA\Post(
     *     path="/auth/carrito/agregar",
     *     summary="Agregar productos al carrito",
     *     description="Servicio para adición de productos al carrito de compra",
     *     operationId="carrito/agregar",
     *     tags={"Carrito"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent( 
     *               @OA\Property(property="id", type="integer", example=1, description="ID del producto a comprar"),
     *               @OA\Property(property="cantidad", type="integer", example=20, description="Cantidad a comprar del producto agregado"),     
     *          ),
     *      ),     
     *      @OA\Response(
     *          response=200,
     *          description="Transacción exitosa",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),     
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Acceso denegado"
     *      ),
     *       @OA\Response(
     *           response=400,
     *           description="Petición no válida"
     *      ),
     *      @OA\Response(
     *           response=404,
     *           description="Servicio no encontrado"
     *       ),
     *       @OA\Response(
     *           response=500,
     *           description="Error de servidor"
     *       ),
     * )
     */

    /**
     * Función para el registro de un nuevo producto
     * @author Johan Morales     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function agregarProducto(Request $request)
    {
        try {
            $usuario = $this->dataUsuario($request);

            $validarProducto = $this->validarProducto($request->id, $request->cantidad);
            $validarProducto = (array) $validarProducto->getData();

            if ($validarProducto['Estado']) {
                Cart::session($usuario['id'])->add(array(
                    'id'    => $request->id,
                    'name'  => $validarProducto['Data']->nombre,
                    'price' => $validarProducto['Data']->precio,
                    'quantity' => $request->cantidad,
                    'attributes' => array()
                ));
                return response()->json(['Estado' => true, 'Respuesta' => 'Producto agregado al carrito', 'Data' => Cart::session($usuario['id'])->getContent()], 200);
            }

            return $validarProducto;
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/auth/carrito/resumen",
     *     summary="Resumen carrito",
     *     description="Servicio para consultar los productos agregados al carrito",
     *     operationId="carrito/resumen",
     *     tags={"Carrito"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(),     
     *      @OA\Response(
     *          response=200,
     *          description="Transacción exitosa",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),     
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Acceso denegado"
     *      ),
     *       @OA\Response(
     *           response=400,
     *           description="Petición no válida"
     *      ),
     *      @OA\Response(
     *           response=404,
     *           description="Servicio no encontrado"
     *       ),
     *       @OA\Response(
     *           response=500,
     *           description="Error de servidor"
     *       ),
     * )
     */

    /**
     * Función para ver el resumen de compra del carrito
     * @author Johan Morales     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function resumenCarrito(Request $request)
    {
        try {
            $usuario = $this->dataUsuario($request);
            return response()->json(['Estado' => true, 'Respuesta' => 'Productos del carrito usuario: ' . $usuario['name'], 'Data' => Cart::session($usuario['id'])->getContent()], 200);
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/auth/carrito/eliminar",
     *     summary="Eliminar productos carrito",
     *     description="Servicio para eliminar productos agregados al carrito",
     *     operationId="carrito/eliminar",
     *     tags={"Carrito"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent( 
     *               @OA\Property(property="id", type="integer", example=1, description="ID del producto a eliminar del carrito"),     
     *          ),
     *      ),     
     *      @OA\Response(
     *          response=200,
     *          description="Transacción exitosa",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),     
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Acceso denegado"
     *      ),
     *       @OA\Response(
     *           response=400,
     *           description="Petición no válida"
     *      ),
     *      @OA\Response(
     *           response=404,
     *           description="Servicio no encontrado"
     *       ),
     *       @OA\Response(
     *           response=500,
     *           description="Error de servidor"
     *       ),
     * )
     */

    /**
     * Función para eliminar un producto del carrito
     * @author Johan Morales     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function eliminarProducto(Request $request)
    {
        try {
            $usuario = $this->dataUsuario($request);
            Cart::session($usuario['id'])->remove($request->id);
            return response()->json(['Estado' => true, 'Respuesta' => 'Producto eliminado del carrito', 'Data' => Cart::session($usuario['id'])->getContent()], 200);
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/auth/carrito/comprar",
     *     summary="Finalizar compra",
     *     description="Servicio para realizar la compra de los productos agregados al carrito",
     *     operationId="carrito/comprar",
     *     tags={"Carrito"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(),     
     *      @OA\Response(
     *          response=200,
     *          description="Transacción exitosa",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),     
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Acceso denegado"
     *      ),
     *       @OA\Response(
     *           response=400,
     *           description="Petición no válida"
     *      ),
     *      @OA\Response(
     *           response=404,
     *           description="Servicio no encontrado"
     *       ),
     *       @OA\Response(
     *           response=500,
     *           description="Error de servidor"
     *       ),
     * )
     */

    /**
     * Función para la compra de productos del carrito
     * @author Johan Morales     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function compraProductos(Request $request)
    {
        try {

            $usuario = $this->dataUsuario($request);
            if (!Cart::session($usuario['id'])->isEmpty()) {
                $productos = Cart::session($usuario['id'])->getTotalQuantity();
                $total = Cart::session($usuario['id'])->getTotal();

                //Guardar la compra
                try {
                    CompraModel::create([
                        'user_id'   => $usuario['id'],
                        'total'     => $total,
                        'productos' => $productos
                    ]);
                } catch (\Throwable $e) {
                    return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción al guardar la compra', 'Data' => $e->getMessage()], 500);
                }

                //Guardar el detalle de la compra
                try {
                    $compra = CompraModel::latest('id')->first();
                    $items = \Cart::getContent();
                    foreach ($items as $data) {
                        DetalleCompraModel::create([
                            'compras_id'  => $compra['id'],
                            'producto_id' => $data->id,
                            'precio'      => $data->price,
                            'cantidad'    => $data->quantity
                        ]);

                        ProductosModel::where('id', $data->id)
                            ->update(['unidades' => DB::raw('unidades - ' . $data->quantity)]);
                    }
                } catch (\Throwable $e) {
                    return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción al guardar el detalle de la compra', 'Data' => $e->getMessage()], 500);
                }

                $respuesta = array(
                    'id'        => $usuario['id'],
                    'usuario'   => $usuario['name'],
                    'productos' => $productos,
                    'total'     => $total,
                    'detalle'   => $items
                );
                Cart::session($usuario['id'])->clear();
                return response()->json(['Estado' => true, 'Respuesta' => 'Su compra ha sido exitosa', 'Data' => $respuesta], 200);
            }
            return response()->json(['Estado' => true, 'Respuesta' => 'No tiene productos en el carrito de compras', 'Data' => []], 200);
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }

    /**
     * Función para consultar la información del usuario logeado
     * @author Johan Morales          
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    private function dataUsuario(Request $request)
    {
        return $request->user();
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
