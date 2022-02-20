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

            //return Excel::store(new ProductosExport($request->inicio, $request->fin), 'invoices.xlsx', 'public');
            return (new ProductosExport($request->inicio, $request->fin))->download('invoices.xlsx');

            //return $compras;
        } catch (\Throwable $e) {
            return response()->json(['Estado' => false, 'Respuesta' => 'Se ha generado una excepción', 'Data' => $e->getMessage()], 500);
        }
    }
}
