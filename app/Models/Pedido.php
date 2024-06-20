<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'id_comprador',
        'fecha_pedido',
        'total_pedido',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($pedido) {
            $pedido->total_pedido = $pedido->detallesPedidos->sum('total');
        });
    }

    public function detallesPedidos()
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido');
    }

    public function comprador()
    {
        return $this->belongsTo(Comprador::class, 'id_comprador');
    }
}

