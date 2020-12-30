<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['updated_at'];

    /**
     * Type of Courses
     *
     * @var string[]
     */
    protected $course_types = ['Course', 'Test'];

    protected $course_live_status = [1,0];

    /**
     * HasMany Relation to @see \App\Model\TranslationCourse
     *
     * @return HasMany
     */
    public function courseTranslations(): HasMany
    {
        return $this->hasMany(TranslationCourse::class);
    }

    /**
     * @param string $languageCode
     * @return Model|HasMany|object|null
     */
    public function defaultCourseTranslation(string $languageCode = 'en')
    {
        $defaultLang = Language::where('language_code', $languageCode)->first();

        return $this->hasMany(TranslationCourse::class)
            ->where('language_id', $defaultLang->id)
            ->first();
    }

    /**
     * @return string[]
     */
    public function getCourseTypes()
    {
        return $this->course_types;
    }

    /**
     * @return int[]
     */
    public function getCourseLiveStatues()
    {
        return $this->course_live_status;
    }
}
