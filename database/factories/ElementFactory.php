<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ElementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstPartNames = ['Great','Cool','Happy','Nice'];
        $secondPartNames = ['Flat','Warehouse','Place'];

        $elementName = $this->faker->randomElement($firstPartNames) .
            ' ' . $this->faker->randomElement($secondPartNames) .
            ' ' . $this->faker->numberBetween(1, 5000);

        return [
            'name' => $elementName,
        ];
    }
}
