<?php

use Illuminate\Database\Seeder;

class BaseCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $baseCourse = new \App\Model\Course();
        $baseCourse->type = 'Base';
        $baseCourse->live = true;
        $baseCourse->save();

//        Create Translation Courses for BaseCourse
        \App\Http\Controllers\CourseController::saveCourseTranslation(
            '',
            '',
            $baseCourse,
            \App\Http\Controllers\CourseController::getLanguage('en')
        );
        \App\Http\Controllers\CourseController::saveCourseTranslation(
            '',
            '',
            $baseCourse,
            \App\Http\Controllers\CourseController::getLanguage('de')
        );
        \App\Http\Controllers\CourseController::saveCourseTranslation(
            '',
            '',
            $baseCourse,
            \App\Http\Controllers\CourseController::getLanguage('fr')
        );
        \App\Http\Controllers\CourseController::saveCourseTranslation(
            '',
            '',
            $baseCourse,
            \App\Http\Controllers\CourseController::getLanguage('ru')
        );
        \App\Http\Controllers\CourseController::saveCourseTranslation(
            '',
            '',
            $baseCourse,
            \App\Http\Controllers\CourseController::getLanguage('es')
        );
        \App\Http\Controllers\CourseController::saveCourseTranslation(
            '',
            '',
            $baseCourse,
            \App\Http\Controllers\CourseController::getLanguage('pt')
        );
    }
}
