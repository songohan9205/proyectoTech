<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductosModel;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para el CRUD de productos
 * @author Johan Morales
 * @return void
 */
class ProductosController extends Controller
{
    /**
     * Función para listar los productos registrados en base de datos
     * @author Johan Morales     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON con la lista de productos
     */
    public function index()
    {
        try {
            $productos = ProductosModel::all();
            if (empty($productos)) {
                return response()->json(['Estado' => true, 'Respuesta' => 'No hay productos registrados en base de datos', 'Data' => $productos], 200);
            }
            return response()->json(['Estado' => true, 'Respuesta' => 'Lista de productos registrados', 'Data' => $productos], 200);
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }

    /**
     * Función para el registro de un nuevo producto
     * @author Johan Morales     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function create(Request $request)
    {
        try {
            $save = ProductosModel::create([
                'nombre' => $request->nombre,
                'precio' => $request->precio,
                'unidades' => $request->unidades
            ]);
            return response()->json(['Estado' => true, 'Respuesta' => 'Producto registrado correctamente', 'Data' => $save], 200);
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }

    /**
     * Función para consultar un producto por id
     * @author Johan Morales     
     * @param  BigInteger $id
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function show($id)
    {
        try {
            $producto = ProductosModel::find($id);
            if (empty($producto)) {
                return response()->json(['Estado' => true, 'Respuesta' => 'No existe un producto con Id: ' . $id, 'Data' => []], 200);
            }
            return response()->json(['Estado' => true, 'Respuesta' => 'Información del producto con Id: ' . $id, 'Data' => $producto], 200);
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }

    /**
     * Función para la actualización de un producto
     * @author Johan Morales    
     * @param  \Illuminate\Http\Request  $request 
     * @param  bigInteger $id 
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function update(Request $request, $id)
    {
        try {
            $producto = ProductosModel::find($id);
            if (empty($producto)) {
                return response()->json(['Estado' => true, 'Respuesta' => 'No existe un producto con Id: ' . $id, 'Data' => []], 200);
            }

            if (count($request->all()) < 1) {
                return response()->json(['Estado' => true, 'Respuesta' => 'No ha ingresado datos para actualizar el producto con Id: ' . $id, 'Data' => []], 200);
            }

            $control = 0;
            isset($request->nombre) ? $producto->nombre = $request->nombre : $control = $control + 1;
            isset($request->precio) ? $producto->precio = $request->precio : $control = $control + 1;
            isset($request->unidades) ? $producto->unidades = $request->unidades : $control = $control + 1;

            if ($control == 3) {
                return response()->json(['Estado' => true, 'Respuesta' => 'Los nombres de atributos ingresados no son los adecuados para actualizar el producto con Id: ' . $id, 'Data' => []], 200);
            }

            $producto->save();
            return response()->json(['Estado' => true, 'Respuesta' => 'Actualización exitosa del producto con Id: ' . $id, 'Data' => $producto], 200);
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }

    /**
     * Función para eliminar un producto
     * @author Johan Morales    
     * @param  bigInteger $id 
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function destroy($id)
    {
        try {
            $producto = ProductosModel::find($id);
            if (empty($producto)) {
                return response()->json(['Estado' => true, 'Respuesta' => 'No existe un producto con Id: ' . $id, 'Data' => []], 200);
            }

            $delete = ProductosModel::where('id', $id)->delete();
            return response()->json(['Estado' => true, 'Respuesta' => 'Se ha eliminado el producto con Id: ' . $id, 'Data' => $delete], 200);
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }
}
