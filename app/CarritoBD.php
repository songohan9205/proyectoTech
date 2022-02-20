<?php

namespace App;

use Darryldecode\Cart\CartCollection;

/**
 * Clase para conexión de BD con el carrito de compras
 * @author Johan Morales
 * @return void
 */
class CarritoBD
{
    public function has($key)
    {
        return CarritoModel::find($key);
    }

    public function get($key)
    {
        if($this->has($key))
        {
            return new CartCollection(CarritoModel::find($key)->productos_info);
        }
        else
        {
            return [];
        }
    }

    public function put($key, $value)
    {
        if($row = CarritoModel::find($key))
        {
            // update
            $row->productos_info = $value;
            $row->save();
        }
        else
        {
            CarritoModel::create([
                'id' => $key,
                'productos_info' => $value
            ]);
        }
    }
}
