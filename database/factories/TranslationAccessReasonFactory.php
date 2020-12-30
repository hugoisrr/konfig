<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\TranslationAccessReason;
use Faker\Generator as Faker;

$factory->define(TranslationAccessReason::class, function (Faker $faker) {
    return [
        'message' => $faker->sentence($nbWords = 4, $variableNbWords = true),
        'access_reason_id' => function () {
            return \App\Model\AccessReason::all()->random();
        },
        'language_id' => function () {
            return \App\Model\Language::all()->random();
        },
    ];
});
