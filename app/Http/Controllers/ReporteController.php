<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CompraModel;
use App\Exports\ProductosExport;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Controlador para lo relacionado con reportes
 * @author Johan Morales
 * @return void
 */
class ReporteController extends Controller
{

    /**
     * @OA\Post(
     *     path="/auth/reporte/ventas",
     *     summary="Ventas por fecha",
     *     description="Servicio para consultar las ventas realizadas de acuerdo al rango de fechas. <b>El informe lo encontrarán en la carpeta storage/app/public del proyecto</b>",
     *     operationId="reporte/ventas",
     *     tags={"Informes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent( 
     *               @OA\Property(property="inicio", type="string", example="2022-02-17", description="Fecha inicial de ventas (YYYY-MM-DD)"),
     *               @OA\Property(property="fin", type="string", example="2022-02-20", description="Fecha final de ventas (YYYY-MM-DD)"),
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
     * Función para generar el informe de ventas por fechas
     * @author Johan Morales          
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function reporteVentas(Request $request)
    {
        try {
            if($request->inicio > $request->fin) {
                return response()->json(['Estado' => true, 'Respuesta' => 'Fecha inicio es mayor a la fecha final', 'Data' => []], 200);
            }

            Excel::store(new ProductosExport($request->inicio, $request->fin), 'Informe_ventas_'.$request->inicio.'_a_'.$request->fin.'.xlsx', 'public');
            return response()->json(['Estado' => true, 'Respuesta' => 'Reporte generado exitosamente', 'Data' => 'Lo encontrará en la carpeta storage/app/public del proyecto'], 500);
            //return (new ProductosExport($request->inicio, $request->fin))->download('invoices.xlsx');
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }
}
