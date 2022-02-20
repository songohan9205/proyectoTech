<?php

namespace App\Exports;

use App\CompraModel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use \Illuminate\Support\Facades\DB;

/**
 * Clase para la exportación de información de compras en XLSX
 * @author Johan Morales
 * @return void
 */
class ProductosExport implements FromQuery, WithHeadings
{
    use Exportable;
    public function __construct(string $inicio, string $fin)
    {
        $this->inicio = $inicio;
        $this->fin = $fin;
    }

    /**
     * Función para generar la consulta a la base de datos
     * @author Johan Morales
     * @param  void
     * @return void
     */
    public function query()
    {
        return CompraModel::query()
            ->whereBetween('created_at', [$this->inicio . ' 00:00:00', $this->fin . ' 23:59:59'])
            ->select(DB::raw('id, total, productos, SUBSTRING(created_at, 1, 10) as fecha'));
    }

    /**
     * Función para colocar cabeceras en el archivo XLSX
     * @author Johan Morales
     * @param  void
     * @return void
     */
    public function headings(): array
    {
        return ["Compra No.", "Total venta", "Productos comprados", "Fecha"];
    }
   
}