@extends('layouts.app')

@section('content')
    <h1>Benutzer</h1>
    @if(count($users) > 1)
        @foreach($users as $user)
            @if($user->id > 1)
                <div class="card card-body bg-light my-4">
                    <div class="row">
                        <div class="col-md">
                            <h3><a href="/users/{{$user->id}}">{{$user->username}}</a></h3>
                            <small>Erstellt am {{date_format($user->created_at, 'd.m.Y')}}</small>
                        </div>
                        <div class="col-md offset-md-4">
                            <h6>Typ:
                                @switch($user->type)
                                    @case(1)
                                        <span class="badge badge-primary">Admin</span>
                                    @break
                                    @default
                                        <span class="badge badge-secondary">Normal</span>
                                @endswitch
                            </h6>
                            <h6>Name: {{$user->name}}</h6>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
        <a href="{{ route('users.create') }}" class="btn btn-primary float-right">Benutzer erstellen</a>
    @else
        <p>Keine Benutzer gefunden!</p>
        <a href="{{ route('users.create') }}" class="btn btn-primary float-right">Benutzer erstellen</a>
    @endif
@endsection
