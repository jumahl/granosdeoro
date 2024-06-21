<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_comprador',
        'fecha_pedido',
        'cantidad',
        'total',
    ];

    public function comprador()
    {
        return $this->belongsTo(Comprador::class, 'id_comprador');
    }

    public function detallesPedidos()
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido');
    }
}

