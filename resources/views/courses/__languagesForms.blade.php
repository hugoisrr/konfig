<ul class="nav nav-tabs mb-3" id="languagesTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="german-tab" data-toggle="tab" href="#german" role="tab" aria-controls="german" aria-selected="true">Deutsch</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="english-tab" data-toggle="tab" href="#english" role="tab" aria-controls="english" aria-selected="true">Englisch</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="french-tab" data-toggle="tab" href="#french" role="tab" aria-controls="french" aria-selected="false">Französisch</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="russian-tab" data-toggle="tab" href="#russian" role="tab" aria-controls="russian" aria-selected="false">Russisch</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="spanish-tab" data-toggle="tab" href="#spanish" role="tab" aria-controls="spanish" aria-selected="false">Spanisch</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="portuguese-tab" data-toggle="tab" href="#portuguese" role="tab" aria-controls="portuguese" aria-selected="false">Portugiesisch</a>
    </li>
</ul>
<div class="tab-content" id="languageTabContent">
    <div class="tab-pane fade show active" id="german" role="tabpanel" aria-labelledby="german-tab">
        <x-language-tab-create-content languageInput="german" languageName="Deutsch" />
    </div>
    <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="english-tab">
        <x-language-tab-create-content languageInput="english" languageName="Englisch" />
    </div>
    <div class="tab-pane fade" id="french" role="tabpanel" aria-labelledby="french-tab">
        <x-language-tab-create-content languageInput="french" languageName="Französisch" />
    </div>
    <div class="tab-pane fade" id="russian" role="tabpanel" aria-labelledby="russian-tab">
        <x-language-tab-create-content languageInput="russian" languageName="Russisch" />
    </div>
    <div class="tab-pane fade" id="spanish" role="tabpanel" aria-labelledby="spanish-tab">
        <x-language-tab-create-content languageInput="spanish" languageName="Spanisch" />
    </div>
    <div class="tab-pane fade" id="portuguese" role="tabpanel" aria-labelledby="portuguese-tab">
        <x-language-tab-create-content languageInput="portuguese" languageName="Portugiesisch" />
    </div>
</div>
