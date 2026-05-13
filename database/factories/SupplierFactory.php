<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'           => $this->faker->company(),
            'code'           => strtoupper($this->faker->lexify('SUP-???')),
            'address'        => $this->faker->address(),
            'contact_person' => $this->faker->name(),
            'email'          => $this->faker->companyEmail(),
            'phone'          => $this->faker->phoneNumber(),
        ];
    }
}
