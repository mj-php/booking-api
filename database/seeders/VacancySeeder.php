<?php

namespace Database\Seeders;

use App\Models\Element;
use App\Models\Vacancy;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class VacancySeeder extends Seeder
{
    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $elements = Element::all();

        foreach ($elements as $element) {
            $dateStart = Carbon::now();

            $randomDays = $this->faker->numberBetween(14,90);
            $vacancyNumber = $this->faker->numberBetween(1,5);

            for ($iterator = 0; $iterator < $randomDays; $iterator++) {
                $vacancy = new Vacancy();
                $vacancy->element_id = $element->id;
                $vacancy->date = $dateStart->add(1,'day');
                $vacancy->number = $vacancyNumber;
                $vacancy->save();
            }
        }
    }
}
