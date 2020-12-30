@extends('layouts.app')

@section('content')
    <a href="{{ route('users.index') }}" class="btn btn-link">Zurück</a>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Bearbeiten Benutzer</div>
                <div class="card-body">
                    <form method="POST" action="/users/{{ $user->id }}">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required autofocus autocomplete="off">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">Username</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control form-control-lg @error('username') is-invalid @enderror" name="username" value="{{ $user->username }}" required autofocus autocomplete="off">
                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required autofocus autocomplete="off">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="userType" class="col-md-4 col-form-label text-md-right">Typ</label>

                            <div class="col-md-6">
                                <select class="form-control" id="userType" name="userType" required>
                                    @foreach($user->getUserTypes() as $key => $value)
                                        <option value="{{$value}}"
                                                @if ($value == $user->type)
                                                selected="selected"
                                            @endif
                                        >@if($value == 1)
                                                Admin
                                            @else
                                                Normal
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="passwordUser" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="passwordUser" autocomplete="off">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control form-control-lg" name="password_confirmation" autocomplete="off">
                            </div>
                        </div>

                        @isset($user->id)
                            {{ method_field('PUT')}}
                        @endisset

                        <div class="form-group row mt-3">
                            <div class="col-md offset-8">
                                <button type="submit" class="btn btn-secondary">
                                    Benutzer speichern
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="form-group row mt-3">
                        <div class="col-md offset-8">
                            <form
                                method="POST"
                                action="/users/{{ $user->id }}"
                                onsubmit="return confirm('Bitte bestätigen Sie, dass Sie die Benutzer löschen möchten.');">
                                @csrf
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-danger">Benutzer löschen</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
