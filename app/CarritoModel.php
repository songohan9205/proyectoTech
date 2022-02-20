<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo tabla Carrito
 * @author Johan Morales
 * @return void
 */
class CarritoModel extends Model
{
    protected $table = 'carrito';    
    protected $fillable = ['id', 'productos_info'];

    public function setProductosInfoAttribute($value)
    {
        $this->attributes['productos_info'] = serialize($value);
    }

    public function getProductosInfoAttribute($value)
    {
        return unserialize($value);
    }
}
