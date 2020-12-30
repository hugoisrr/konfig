@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Bearbeiten eines Kurses.</div>
                <div class="card-body">
                    <form method="POST" action="/courses/{{ $course->id }}">
                        @csrf
                        {{--Languages forms--}}
                        <h6>Übersetzungen des Kurses <small class="text-muted">Alle Sprachen müssen ausgefüllt sein.</small></h6>
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
                                <x-language-tab-edit-content :course="$course" languageCode="de" languageName="Deutsch" languageInput="german" />
                            </div>
                            <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="english-tab">
                                <x-language-tab-edit-content :course="$course" languageCode="en" languageName="Englisch" languageInput="english" />
                            </div>
                            <div class="tab-pane fade" id="french" role="tabpanel" aria-labelledby="french-tab">
                                <x-language-tab-edit-content :course="$course" languageCode="fr" languageName="Französisch" languageInput="french" />
                            </div>
                            <div class="tab-pane fade" id="russian" role="tabpanel" aria-labelledby="russian-tab">
                                <x-language-tab-edit-content :course="$course" languageCode="ru" languageName="Russisch" languageInput="russian" />
                            </div>
                            <div class="tab-pane fade" id="spanish" role="tabpanel" aria-labelledby="spanish-tab">
                                <x-language-tab-edit-content :course="$course" languageCode="es" languageName="Spanisch" languageInput="spanish" />
                            </div>
                            <div class="tab-pane fade" id="portuguese" role="tabpanel" aria-labelledby="portuguese-tab">
                                <x-language-tab-edit-content :course="$course" languageCode="pt" languageName="Portugiesisch" languageInput="portuguese" />
                            </div>
                        </div>
                        <hr />
                        <div class="form-group row">
                            <div class="col-md-2 offset-1">
                                <label for="type" class="col-form-label text-md-right">Typ</label>
                                <select class="form-control" id="type" name="type" required autofocus>
                                    @foreach($course->getCourseTypes() as $key => $value)
                                        <option value="{{$value}}"
                                                @if ($value == $course->type)
                                                    selected="selected"
                                                @endif
                                        >{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                @if(Auth::user()->type == 1)
                                    <label for="liveStatus" class="col-form-label text-md-right">Live Status</label>
                                    <select class="form-control" id="liveStatus" name="liveStatus" required autofocus>
                                        @foreach($course->getCourseLiveStatues() as $key => $value)
                                            <option value="{{$value}}"
                                                    @if ($value == $course->live)
                                                    selected="selected"
                                                @endif
                                            >@if($value == 1)
                                                    Live
                                                @else
                                                    Nicht Live
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <label for="iap_id_apple" class="col-form-label text-md-right">iap id apple</label>

                                <input id="iap_id_apple" type="text" class="form-control @error('iap_id_apple') is-invalid @enderror" name="iap_id_apple" value="{{ $course->iap_id_apple }}" autofocus>
                            </div>
                            <div class="col-md-3">
                                <label for="iap_id_google" class="col-form-label text-md-right">iap id google</label>

                                <input id="iap_id_google" type="text" class="form-control @error('iap_id_google') is-invalid @enderror" name="iap_id_google" value="{{ $course->iap_id_google }}" autofocus>
                            </div>
                        </div>
                        @isset($course->id)
                            {{ method_field('PUT')}}
                        @endisset

                        <div class="form-group row mt-3">
                            <div class="col-md">
                                <a class="btn btn-outline-danger" role="button" href="/courses/{{$course->id}}">Abbrechen</a>
                            </div>
                            <div class="col-md offset-6">
                                <button type="submit" class="btn btn-secondary">
                                    Kurs speichern
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
