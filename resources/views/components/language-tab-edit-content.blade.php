<div class="form-group row">
    <label for="title-{{ $languageInput }}" class="col-md-2 col-form-label text-md-right">Titel</label>

    <div class="col-md-9">
        <input
            id="title-{{ $languageInput }}"
            type="text"
            class="form-control form-control-lg @error('title-'.$languageInput) is-invalid @enderror"
            name="title-{{ $languageInput }}"
            value="{{ old('title-'.$languageInput, $course->defaultCourseTranslation($languageCode)->title) }}"
            autofocus>
        <small id="title-{{ $languageInput }}Help" class="form-text text-muted">Auf {{ $languageName }}</small>
        @error('title-'.$languageInput)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <label for="description-{{ $languageInput }}" class="col-md-2 col-form-label text-md-right">Beschreibung</label>

    <div class="col-md-9">
        <textarea
            id="description-{{ $languageInput }}"
            class="form-control @error('description-'.$languageInput) is-invalid @enderror"
            name="description-{{ $languageInput }}" rows="5"
            autofocus>{{ old('description-'.$languageInput, $course->defaultCourseTranslation($languageCode)->description) }}</textarea>
        <small id="description-{{ $languageInput }}Help" class="form-text text-muted">Auf {{ $languageName }}</small>
        @error('description-'.$languageInput)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
