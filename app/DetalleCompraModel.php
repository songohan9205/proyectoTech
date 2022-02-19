<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo tabla Detalle Compra
 * @author Johan Morales
 * @return void
 */
class DetalleCompraModel extends Model
{
    protected $table = 'detalle_compra';
    protected $guarded = ['id'];
    protected $fillable = ['compras_id', 'producto_id', 'precio', 'cantidad'];
}
