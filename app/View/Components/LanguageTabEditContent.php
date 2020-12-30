<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LanguageTabEditContent extends Component
{
    public $course;
    public $languageCode;
    public $languageName;
    public $languageInput;

    public function __construct($course, $languageCode, $languageName, $languageInput)
    {
        $this->course = $course;
        $this->languageName = $languageName;
        $this->languageCode = $languageCode;
        $this->languageInput = $languageInput;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.language-tab-edit-content');
    }
}
