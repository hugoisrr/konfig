<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\File;
use Faker\Generator as Faker;

$factory->define(File::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'path' => $faker->url,
        'type' => $faker->randomElement(['m-audio', 'f-audio', 'tree']),
        'translation_course_id' => function () {
            return \App\Model\TranslationCourse::all()->random();
        },
    ];
});
