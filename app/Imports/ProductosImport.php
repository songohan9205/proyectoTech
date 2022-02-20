<?php

namespace App\Imports;

use App\ProductosModel;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

/**
 * Clase para la importación de archivos CSV en cargue masivo de productos
 * @author Johan Morales
 * @return void
 */
class ProductosImport implements ToModel, WithCustomCsvSettings
{
    /**
     * Función para el registro de los productos del CSV en base de datos
     * @author Johan Morales
     * @param  array  $row
     * @return \Illuminate\Http\Response JSON con la respuesta del proceso
     */
    public function model(array $row)
    {        
        return new ProductosModel([
            'nombre'    => $row[0],
            'precio'    => $row[1],
            'unidades'  => $row[2]
        ]);
    }

    /**
     * Función para definir el separador de columnas
     * @author 
     * @return void
     */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ";"
        ];
    }
}
