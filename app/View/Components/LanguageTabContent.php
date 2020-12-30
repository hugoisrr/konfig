<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LanguageTabContent extends Component
{
    public $course;
    public $languageCode;
    public $languageName;

    public function __construct($course, $languageCode, $languageName)
    {
        $this->course = $course;
        $this->languageCode = $languageCode;
        $this->languageName = $languageName;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.language-tab-content');
    }
}
