<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompraModel extends Model
{
    protected $table = 'compras';
    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'total', 'productos'];
}

