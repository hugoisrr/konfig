<?php

use Illuminate\Database\Seeder;

class FactoryFakeModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class, 3)->create();
        factory(\App\Model\Course::class, 10)->create();
        factory(\App\Model\TranslationCourse::class, 35)->create();
        factory(\App\Model\File::class, 35)->create();
    }
}
