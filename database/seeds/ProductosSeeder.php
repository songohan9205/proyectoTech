<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

/**
* Seeder para una precarga de productos en la base de datos
* @author Johan Morales
* @return void
*/
class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('productos')->insert([
            [
                'nombre' => 'Balón Golty',                
                'precio' => "50000",
                'unidades' => "100",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'nombre' => 'Balón Mikasa',
                'precio' => "40000",
                'unidades' => "100",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'nombre' => 'Raqueta ping pong',                
                'precio' => "30000",
                'unidades' => "200",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'nombre' => 'Pantalón sudadera hombre',                
                'precio' => "60000",
                'unidades' => "300",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'nombre' => 'Pantalón sudadera mujer',                
                'precio' => "70000",
                'unidades' => "250",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'nombre' => 'Tennis Nike Armor',                
                'precio' => "250000",
                'unidades' => "30",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'nombre' => 'Tennis Adidas Cromo',                
                'precio' => "300000",
                'unidades' => "20",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);

    }
}
