<?php

namespace Database\Factories;

use App\Models\Rule;
use Illuminate\Database\Eloquent\Factories\Factory;

class RuleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'rule_name' => $this->faker->unique()->numerify('###-###'),
            'user_type' => 'visitors',
            'unit_type' => '5R',
            'max_limit' => $this->faker->unique()->numberBetween(2, 6),
            'starts_on' => $this->faker->unique()->dateTimeBetween('this week', '+20 days'),
            'ends_on' => $this->faker->unique()->dateTimeBetween('next week', '+10 days'),
        ];
    }
}
