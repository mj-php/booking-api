<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $days = $this->faker->numberBetween(1, 4);

        return [
            'days' => $days + 1,
            'vacancies' => $this->faker->numberBetween(1, 3),
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays($days)
        ];
    }
}
