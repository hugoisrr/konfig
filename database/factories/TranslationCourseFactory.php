<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\TranslationCourse;
use Faker\Generator as Faker;

$factory->define(TranslationCourse::class, function (Faker $faker) {
    return [
        'version' => $faker->numberBetween($min = 1, $max = 14),
        'title' => $faker->sentence($nbWords = 4, $variableNbWords = true),
        'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
        'course_id' => function () {
            return \App\Model\Course::all()->random();
        },
        'language_id' => function () {
            return \App\Model\Language::all()->random();
        },
    ];
});
