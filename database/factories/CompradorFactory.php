<?php

namespace Database\Factories;

use App\Models\Comprador;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompradorFactory extends Factory
{
    protected $model = Comprador::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word,
            'direccion' => $this->faker->sentence,
            'contacto' => $this->faker->sentence,
          
        ];
    }
}
