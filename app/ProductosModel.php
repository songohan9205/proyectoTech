<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductosModel extends Model
{
    protected $table = 'productos';
    protected $guarded = ['id'];
    protected $fillable = ['nombre', 'precio', 'unidades'];
}
