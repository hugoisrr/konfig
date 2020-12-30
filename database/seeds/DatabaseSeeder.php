<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        Creates Main Admin User
        $this->call([MainAdminSeeder::class]);
//        Insert languages in DB
        $this->call([LanguageTableSeeder::class]);
//        Creation of a BaseCourse and its respective Translation Courses
        $this->call([BaseCourseSeeder::class]);
//        Populate reasons for admission
        $this->call([AccessReasonSeeder::class]);
//        Populate DB with Fake data
//        $this->call([FactoryFakeModelsSeeder::class]);
    }
}
