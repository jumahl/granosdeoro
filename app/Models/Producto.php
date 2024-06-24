<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'cantidad_en_existencia'];

    public function detallesPedidos()
    {
        return $this->hasMany(DetallePedido::class, 'id_producto');
    }
    public function reducirCantidad($cantidad)
    {
        if ($this->cantidad_en_existencia >= $cantidad) {
            $this->cantidad_en_existencia -= $cantidad;
            $this->save();
        } else {
            throw new \Exception('No hay suficiente cantidad en existencia');
        }
    }
}
