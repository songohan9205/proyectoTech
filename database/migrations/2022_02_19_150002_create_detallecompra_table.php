<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

/**
* Migración para guardar los datos del detalle de compra
* @author Johan Morales
* @return void
*/
class CreateDetallecompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_compra', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('compras_id')->unsigned();
            $table->bigInteger('producto_id')->unsigned();
            $table->float('precio', 10, 2);
            $table->integer('cantidad');
            $table->timestamps();
            
            $table->foreign('compras_id')->references('id')->on('compras');             
            $table->foreign('producto_id')->references('id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('detalle_compra');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
