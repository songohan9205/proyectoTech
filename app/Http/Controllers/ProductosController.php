<?php

namespace App\Http\Controllers;

use App\Imports\ProductosImport;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\ProductosModel;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Controlador para el CRUD de productos
 * @author Johan Morales
 * @return void
 */
class ProductosController extends Controller
{
    /**
     * @OA\Get(
     *     path="/auth/productos",
     *     summary="Listar productos",
     *     description="Servicio para visualizar todos los productos registrados en la base de datos",
     *     operationId="productos",
     *     tags={"Productos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody( ),       
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
     * @OA\Post(
     *     path="/auth/productos/nuevo",
     *     summary="Nuevo producto",
     *     description="Servicio para el registro de un nuevo producto",
     *     operationId="productos/nuevo",
     *     tags={"Productos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent( required={"nombre", "precio", "unidades"},
     *               @OA\Property(property="nombre", type="string", example="Nombre del producto", maxLength=150, description="Nombre del producto a crear"),
     *               @OA\Property(property="precio", type="float", example=150000, description="Precio del producto"),
     *               @OA\Property(property="unidades", type="integer", example=150, description="Unidades disponibles del producto"),
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
    public function nuevo(Request $request)
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
     * @OA\Get(
     *     path="/auth/productos/buscar/{producto}",
     *     summary="Consultar producto por ID",
     *     description="Servicio para consultar un producto en específicdo",
     *     operationId="productos/buscar/{producto}",
     *     tags={"Productos"},
     *     security={{"bearerAuth":{}}},     
     *     @OA\Parameter(
     *      description="ID del producto",
     *      in="path",
     *      name="producto",
     *      required=true,
     *      example="1",
     *      @OA\Schema(
     *       type="integer",
     *       format="int64"
     *      )
     * ),
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
     * Función para consultar un producto por id
     * @author Johan Morales     
     * @param  BigInteger $id
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function buscar($id)
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
     * @OA\Put(
     *     path="/auth/productos/actualizar/{producto}",
     *     summary="Actualizar producto",
     *     description="Servicio para la actualización de datos de un producto",
     *     operationId="productos/actualizar/{producto}",
     *     tags={"Productos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent( 
     *               @OA\Property(property="nombre", type="string", example="Nombre del producto", maxLength=150, description="Nombre del producto modificado"),
     *               @OA\Property(property="precio", type="float", example=150000, description="Precio del producto modificado"),
     *               @OA\Property(property="unidades", type="integer", example=150, description="Unidades disponibles del producto modificadas"),
     *          ),
     *      ),
     *     @OA\Parameter(
     *          description="ID del producto",
     *          in="path",
     *          name="producto",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *          type="integer",
     *          format="int64"
     *          )
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
     * Función para la actualización de un producto
     * @author Johan Morales    
     * @param  \Illuminate\Http\Request  $request 
     * @param  bigInteger $id 
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function actualizar(Request $request, $id)
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
     * @OA\Delete(
     *     path="/auth/productos/eliminar/{producto}",
     *     summary="Eliminar producto",
     *     description="Servicio para eliminar un producto",
     *     operationId="productos/eliminar/{producto}",
     *     tags={"Productos"},
     *     security={{"bearerAuth":{}}},     
     *     @OA\Parameter(
     *          description="ID del producto",
     *          in="path",
     *          name="producto",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *          type="integer",
     *          format="int64"
     *          )
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
     * Función para eliminar un producto
     * @author Johan Morales    
     * @param  bigInteger $id 
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function eliminar($id)
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

    /**
     * @OA\Post(
     *     path="/auth/productos/carga",
     *     summary="Carga masiva productos",
     *     description="Servicio para el registro masivo de productos por medio de un archivo CSV",
     *     operationId="productos/carga",
     *     tags={"Productos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      title="archivo",
     *                      property="archivo",
     *                      description="Archivo CSV",
     *                      type="file",
     *                   ),
     *               ),
     *           ),
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
     * Función para carga masiva de productos
     * @author Johan Morales    
     * @param  \Illuminate\Http\Request  $request 
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function cargaMasiva(Request $request)
    {
        try {
            if(empty($request->archivo) || $request->archivo == null) {
                return response()->json(['Estado' => true, 'Respuesta' => 'No ha seleccionado ningún archivo para cargar', 'Data' => []], 200);
            }
            $extension = pathinfo($request->archivo->getClientOriginalName(), PATHINFO_EXTENSION);
            $nameField = pathinfo($request->archivo->getClientOriginalName(), PATHINFO_FILENAME);
            if ($extension == 'csv' || $extension == 'CSV') {
                $file = $request->file('archivo');
                $csv  = $nameField . '.' . $extension;
                $file->move(storage_path('app'), $csv);
                $path = storage_path('app/') . $csv;

                Excel::import(new ProductosImport, $path);
                File::delete(storage_path('app/' . $csv));
                return response()->json(['Estado' => true, 'Respuesta' => 'Se ha cargado en la base de datos los productos del CSV', 'Data' => []], 200);
            }
            return response()->json(['Estado' => true, 'Respuesta' => 'El archivo no puede ser procesado', 'Data' => 'Recuerde! Únicamente se procesan archivos .csv'], 200);
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }
}
