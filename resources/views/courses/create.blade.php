@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
            <div class="card-header">Kurs erstellen</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('courses.store') }}">
                        @csrf
                        {{--Languages forms--}}
                        <h6>Übersetzungen des Kurses <small class="text-muted">Alle Sprachen müssen ausgefüllt sein.</small></h6>
                            @include('courses.__languagesForms')
                        <hr />
                        <div class="form-group row">
                            <div class="col-md-3 offset-md-2">
                                <label for="type" class="col-form-label text-md-right">Typ</label>

                                <select class="form-control" id="type" name="type" required autofocus>
                                    <option>Course</option>
                                    <option>Test</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="iap_id_apple" class="col-form-label text-md-right">iap id apple</label>

                                <input id="iap_id_apple" type="text" class="form-control @error('iap_id_apple') is-invalid @enderror" name="iap_id_apple" value="{{ old('iap_id_apple') }}" autofocus>
                            </div>
                            <div class="col-md-3">
                                <label for="iap_id_google" class="col-form-label text-md-right">iap id google</label>

                                <input id="iap_id_google" type="text" class="form-control @error('iap_id_google') is-invalid @enderror" name="iap_id_google" value="{{ old('iap_id_google') }}" autofocus>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-3 offset-md-9">
                                <button type="submit" class="btn btn-primary">
                                    Erstellen
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
