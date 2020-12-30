@extends('layouts.app')

@section('content')
    <a href="{{ route('courses.index') }}" class="btn btn-link">Zurück</a>
    <div class="jumbotron">
        <div class="d-flex flex-row-reverse">
            <div class="p-2">
                Status:
                @if($course->live == true)
                    <span class="badge badge-pill badge-success">Live</span>
                @else<span class="badge badge-pill badge-info">Nicht Live</span>
                @endif
            </div>
            <div class="p-2">
                Typ:
                @switch($course->type)
                    @case('Course')
                    <span class="badge badge-secondary">Course</span>
                    @break
                    @case('Test')
                    <span class="badge badge-warning">Test</span>
                    @break
                @endswitch
            </div>
        </div>
        <ul class="nav nav-tabs" id="languagesTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="german-tab" data-toggle="tab" href="#german" role="tab" aria-controls="german" aria-selected="false">Deutsch</a>
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
        <div class="tab-content" id="languagesTabContent">
            <div class="tab-pane fade show active" id="german" role="tabpanel" aria-labelledby="german-tab">
                <x-language-tab-content :course="$course" languageCode="de" languageName="Deutsch" />
            </div>
            <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="english-tab">
                <x-language-tab-content :course="$course" languageCode="en" languageName="Englisch" />
            </div>
            <div class="tab-pane fade" id="french" role="tabpanel" aria-labelledby="french-tab">
                <x-language-tab-content :course="$course" languageCode="fr" languageName="Französisch" />
            </div>
            <div class="tab-pane fade" id="russian" role="tabpanel" aria-labelledby="russian-tab">
                <x-language-tab-content :course="$course" languageCode="ru" languageName="Russisch" />
            </div>
            <div class="tab-pane fade" id="spanish" role="tabpanel" aria-labelledby="spanish-tab">
                <x-language-tab-content :course="$course" languageCode="es" languageName="Spanisch" />
            </div>
            <div class="tab-pane fade" id="portuguese" role="tabpanel" aria-labelledby="portuguese-tab">
                <x-language-tab-content :course="$course" languageCode="pt" languageName="Portugiesisch" />
            </div>
        </div>
        <hr/>
        <div class="d-flex">
            <div class="p-2 mr-auto">
                <small class="text-muted">Erstellt am {{date_format($course->created_at, 'd.m.Y')}}</small> <br/>
                <small class="text-muted">Hochgeladen am {{date_format($course->updated_at, 'd.m.Y H:i:s')}}</small>
            </div>
            @if($course->type == 'Course')
                <div class="p-2">
                    <p>iap id google: <strong>{{$course->iap_id_google}}</strong></p>
                </div>
                <div class="p-2">
                    <p>iap id apple: <strong>{{$course->iap_id_apple}}</strong></p>
                </div>
            @endif
        </div>
        <hr/>
        <a href="/courses/{{ $course->id }}/edit" class="btn btn-info">Bearbeiten</a>
        @if (Auth::user()->type == 1)
            <button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#deleteModalConfirm">
                Kurs löschen
            </button>
        @endif
        
        <!-- Modal -->
        <div class="modal fade" id="deleteModalConfirm" tabindex="-1" role="dialog" aria-labelledby="deleteCourseLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="deleteCourseLabel">Löschkurs bestätigen.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    Bitte bestätigen Sie, dass Sie den Kurs löschen möchten.
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                <form method="POST" action="/courses/{{ $course->id }}">
                    @csrf
                    {{ @method_field('DELETE') }}
                    <button type="submit" class="btn btn-danger">Ja, Kurs löschen</button>
                </form>    
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
