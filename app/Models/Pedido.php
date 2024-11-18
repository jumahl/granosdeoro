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
        'total',
        'status',
    ];

    // Define el método detallesPedidos
    public function detallesPedidos()
    {
        // Suponiendo que tienes una relación con un modelo DetallePedido
        return $this->hasMany(DetallePedido::class, 'id_pedido');
    }
}