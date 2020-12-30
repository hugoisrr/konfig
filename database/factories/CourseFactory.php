<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Course;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['Course', 'Test']),
        'iap_id_apple' => $faker->word,
        'iap_id_google' => $faker->word,
        'live' => $faker->boolean,
    ];
});
