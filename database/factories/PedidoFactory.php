<?php

namespace Database\Factories;

use App\Models\Pedido;
use App\Models\Comprador;
use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    public function definition()
    {
        return [
            'id_comprador' => Comprador::factory(),
            'fecha_pedido' => $this->faker->date(),
            'total' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement(['pendiente', 'completado', 'cancelado']),
        ];
    }
}