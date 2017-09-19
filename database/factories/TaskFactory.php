<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Task::class, function (Faker\Generator $faker) {

    return [
        'title' => $faker->sentence(4),
        'description' => $faker->text(100),
        'due_date' => $faker->dateTimeBetween('-0 year')->format('Y-m-d'),
        'completed' => $faker->randomElement(['true', 'false']),
        'created_at' => $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
        'updated_at' => $faker->dateTimeBetween('-2 months')->format('Y-m-d H:i:s'),
    ];
});
