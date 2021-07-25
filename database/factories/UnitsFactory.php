<?php

namespace Database\Factories;

use App\Models\Units;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Units::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'block_no' => $this->faker->numberBetween(1, 8),
            'unit_no' => $this->faker->unique()->numerify('###-###'),
            'type' => $this->faker->numberBetween(1, 6).'RF',
            'contact_number' => $this->faker->unique()->numberBetween(6000000, 90000000),
        ];
    }
}
