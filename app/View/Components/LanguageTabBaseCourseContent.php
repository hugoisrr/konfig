<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LanguageTabBaseCourseContent extends Component
{
    public $baseCourse;
    public $languageCode;

    public function __construct($baseCourse, $languageCode)
    {
        $this->baseCourse = $baseCourse;
        $this->languageCode = $languageCode;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.language-tab-base-course-content');
    }
}
