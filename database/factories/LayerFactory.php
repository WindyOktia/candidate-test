<?php

namespace Database\Factories;

use App\Models\Layup;
use Illuminate\Database\Eloquent\Factories\Factory;

class LayerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'layup_id'    => Layup::factory(),
            'layer_order' => $this->faker->numberBetween(1, 10),
            'thickness'   => $this->faker->randomFloat(2, 20, 200),
            'width'       => $this->faker->randomFloat(2, 100, 1000),
            'angle'       => $this->faker->randomElement([0, 45, 90, -45, -90]),
        ];
    }
}
