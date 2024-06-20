<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $fillable = [
        'id_producto',
        'cantidad',
        'total',
        'id_pedido',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($detallePedido) {
            $producto = Producto::find($detallePedido->id_producto);
            $detallePedido->total = $producto ? $producto->precio * $detallePedido->cantidad : 0;
        });
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}
