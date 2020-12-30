@extends('layouts.app')

@section('content')
    <h1>Kurse</h1>
    <div class="row mb-3">
        <div class="col-md-2 offset-3">
            <form action="/filterStatusCourse" method="GET">
                <div class="input-group">
                    <select class="custom-select" id="filterStatus" name="filterStatus">
                        <option value="all" selected>Status...</option>
                        <option value="1">Live</option>
                        <option value="0">Nicht Live</option>
                    </select>
                    <span class="input-group-prepend">
                    <button type="submit" class="btn btn-primary">Suche</button>
                </span>
                </div>
            </form>
        </div>
        <div class="col-md-2">
            <form action="/filterTypeCourse" method="GET">
                <div class="input-group">
                    @csrf
                    <select class="custom-select" id="filterType" name="filterType">
                        <option value="all" selected>Typ...</option>
                        <option value="Course">Kurs</option>
                        <option value="Test">Test</option>
                    </select>
                    <span class="input-group-prepend">
                        <button type="submit" class="btn btn-primary">Suche</button>
                    </span>
                </div>
            </form>
        </div>
        <div class="col-md">
            <form action="/searchCourse" method="GET">
                @csrf
                <div class="input-group">
                    <input type="search" name="searchCourse" id="searchCourse" class="form-control" placeholder="Suchen Sie einen Kurs nach Titel oder ID">
                    <span class="input-group-prepend">
                        <button type="submit" class="btn btn-primary">Suche</button>
                    </span>
                    <span class="input-group-prepend">
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">Reset</a>
                    </span>
                </div>
            </form>
        </div>
    </div>
    @if(count($courses) > 0)
        @foreach($courses as $course)
            <div class="card card-body bg-light my-4">
                <div class="row">
                    <div class="col-md">
                        <h3><a href="{{ route('courses.show', [$course]) }}">{{$course->defaultCourseTranslation('de')->title ?? 'UNSET'}}</a></h3>
                        <small>Erstellt am {{date_format($course->created_at, 'd.m.Y')}}</small> <br/>
                        <small>Hochgeladen am {{date_format($course->updated_at, 'd.m.Y H:i:s')}}</small>
                    </div>
                    <div class="col-md offset-md-4">
                        <h6>Typ:
                            @switch($course->type)
                                @case('Base')
                                    <span class="badge badge-primary">Base</span>
                                    @break
                                @case('Course')
                                    <span class="badge badge-secondary">Course</span>
                                    @break
                                @case('Test')
                                    <span class="badge badge-warning">Test</span>
                                    @break
                            @endswitch
                        </h6>
                        <h6>Status:
                            @if($course->live == true)
                                <span class="badge badge-pill badge-success">Live</span>
                            @else<span class="badge badge-pill badge-info">Nicht Live</span>
                            @endif
                        </h6>
                        <h6>Id: {{$course->id}}</h6>
                    </div>
                </div>
            </div>
        @endforeach
        {{$courses->links()}}
        <a href="/courses/create" class="btn btn-primary float-right">Kurs erstellen</a>
    @else
        <p>Keine Kurse gefunden!</p>
        <a href="/courses/create" class="btn btn-primary float-right">Kurs erstellen</a>
    @endif
@endsection
