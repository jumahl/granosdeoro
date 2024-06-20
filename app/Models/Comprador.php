<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprador extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'direccion', 'contacto'];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_comprador');
    }
}
