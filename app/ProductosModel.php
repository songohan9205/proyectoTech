<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo tabla Productos
 * @author Johan Morales
 * @return void
 */
class ProductosModel extends Model
{
    protected $table = 'productos';
    protected $guarded = ['id'];
    protected $fillable = ['nombre', 'precio', 'unidades'];
}
