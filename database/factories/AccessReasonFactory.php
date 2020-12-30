<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\AccessReason;
use Faker\Generator as Faker;

$factory->define(AccessReason::class, function (Faker $faker) {
    return [
        'status' => $faker->unique()->numberBetween($min = 0, $max = 100),
    ];
});
