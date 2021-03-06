<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LanguageTabCreateContent extends Component
{
    public $languageInput;
    public $languageName;

    public function __construct($languageInput, $languageName)
    {
        $this->languageInput = $languageInput;
        $this->languageName = $languageName;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.language-tab-create-content');
    }
}
