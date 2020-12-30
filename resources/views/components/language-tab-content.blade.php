<h1 class="display-4">{{$course->defaultCourseTranslation($languageCode)->title ?? 'UNSET'}}</h1>
<small class="text-muted">Version: {{$course->defaultCourseTranslation($languageCode)->version ?? 'UNSET'}}</small>
<div class="lead mt-2">
    {!! $course->defaultCourseTranslation($languageCode)->description ?? 'UNSET' !!}
</div>
<div class="form-group row mt-5">
    <div class="col-md">
        <form method="POST" action="{{ route('uploadFile') }}" enctype="multipart/form-data">
            @csrf
            <label for="femaleFilesUpload"><p class="font-weight-bold">Weibliche Sprachdateien.</p></label>
            <small class="text-muted">Auf {{ $languageName }}.</small>
            <input type="file" accept=".mp3" class="form-control-file" name="femaleFilesUpload[]" multiple>
            <input type="hidden" value="female" name="sex">
            <input type="hidden" value="{{$course->id}}" name="courseId">
            <input type="hidden" value="{{ $languageCode }}" name="language">
            <input type="hidden" value="{{$course->defaultCourseTranslation($languageCode)->id}}" name="courseTranslationId">
            <button type="submit" class="btn btn-secondary btn-sm mt-3">Hochladen</button>
        </form>
    </div>
    <div class="col-md">
        <form method="POST" action="{{ route('uploadFile') }}" enctype="multipart/form-data">
            @csrf
            <label for="maleFilesUpload"><p class="font-weight-bold">Männliche Sprachdateien.</p></label>
            <small class="text-muted">Auf {{ $languageName }}.</small>
            <input type="file" accept=".mp3" class="form-control-file" name="maleFilesUpload[]" multiple>
            <input type="hidden" value="masculine" name="sex">
            <input type="hidden" value="{{$course->id}}" name="courseId">
            <input type="hidden" value="{{ $languageCode }}" name="language">
            <input type="hidden" value="{{$course->defaultCourseTranslation($languageCode)->id}}" name="courseTranslationId">
            <button type="submit" class="btn btn-secondary btn-sm mt-3">Hochladen</button>
        </form>
    </div>
    <div class="col-md">
        <form method="POST" action="{{ route('uploadXMLFile') }}" enctype="multipart/form-data">
            @csrf
            <label for="xmlFileUpload"><p class="font-weight-bold">XML-Dateien.</p></label>
            <small class="text-muted">Auf {{ $languageName }}.</small>
            <input type="file" accept=".xml" class="form-control-file" name="xmlFileUpload">
            <input type="hidden" value="{{$course->id}}" name="courseId">
            <input type="hidden" value="{{ $languageCode }}" name="language">
            <input type="hidden" value="{{$course->defaultCourseTranslation($languageCode)->id}}" name="courseTranslationId">
            <button type="submit" class="btn btn-secondary btn-sm mt-3">Hochladen</button>
        </form>
    </div>
</div>
<div class="form-group row mt-3">
    <div class="card mt-3" style="width: 100%">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md">
                    <h5 class="card-title">Liste der Dateien</h5>
                </div>
                <div class="col-md">
                    <input type="text" class="form-control" id="searchFile{{ucfirst($languageCode)}}" placeholder="Datei durchsuchen">
                </div>
                <div class="col-md">
                    <select class="custom-select" id="filterType{{ucfirst($languageCode)}}">
                        <option value="all" selected>Typ...</option>
                        <option value="f-audio">Weibliche</option>
                        <option value="m-audio">Männliche</option>
                        <option value="xml">XML</option>
                    </select>
                </div>
            </div>
            @if(count($course->defaultCourseTranslation($languageCode)->files) > 0)
                <table class="table table-striped" id="translationCourseFiles{{ucfirst($languageCode)}}">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Typ</th>
                        <th scope="col">Erstellt am</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($course->defaultCourseTranslation($languageCode)->files as $file)
                        <tr id="files">
                            <td>{{$file->name}}</td>
                            <td>{{$file->type}}</td>
                            <td>
                                <small class="text-muted">{{date_format($file->created_at, 'd.m.Y')}}</small>
                            </td>
                            <td>
                                <form
                                    method="POST"
                                    action="{{ route('downloadFile', [
                                        'id' => $file->id
                                        ]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">Datei</button>
                                </form>
                            </td>
                            <td>
                                <form
                                    method="POST"
                                    action="{{ route('deleteFile', [
                                        'id' => $file->id,
                                        'courseTranslationId' => $course->defaultCourseTranslation($languageCode)->id
                                        ]) }}"
                                    onsubmit="return confirm('Bitte bestätigen Sie, dass Sie die Datei löschen möchten.');">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Datei löschen</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <h6 class="card-subtitle mb-2 text-muted">Keine Dateien gefunden.</h6>
            @endif
        </div>
    </div>
</div>
