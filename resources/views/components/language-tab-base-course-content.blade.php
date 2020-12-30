<small class="text-muted">Version: {{$baseCourse->defaultCourseTranslation($languageCode)->version ?? 'UNSET'}}</small>
<div class="form-group row mt-3">
    <div class="col-md">
        <form method="POST" action="{{ route('uploadFile') }}" enctype="multipart/form-data">
            @csrf
            <label for="femaleFilesUpload"><p class="font-weight-bold">Weibliche Sprachdateien.</p></label>
            <input type="file" accept=".mp3" class="form-control-file" name="femaleFilesUpload[]" multiple>
            <input type="hidden" value="female" name="sex">
            <input type="hidden" value="{{$baseCourse->id}}" name="courseId">
            <input type="hidden" value="{{ $languageCode }}" name="language">
            <input type="hidden" value="{{$baseCourse->defaultCourseTranslation($languageCode)->id}}" name="courseTranslationId">
            <button type="submit" class="btn btn-secondary btn-sm mt-3">Hochladen</button>
        </form>
    </div>
    <div class="col-md">
        <form method="POST" action="{{ route('uploadFile') }}" enctype="multipart/form-data">
            @csrf
            <label for="maleFilesUpload"><p class="font-weight-bold">Männliche Sprachdateien.</p></label>
            <input type="file" accept=".mp3" class="form-control-file" name="maleFilesUpload[]" multiple>
            <input type="hidden" value="masculine" name="sex">
            <input type="hidden" value="{{$baseCourse->id}}" name="courseId">
            <input type="hidden" value="{{ $languageCode }}" name="language">
            <input type="hidden" value="{{$baseCourse->defaultCourseTranslation($languageCode)->id}}" name="courseTranslationId">
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
                    </select>
                </div>
            </div>
            @if(count($baseCourse->defaultCourseTranslation($languageCode)->files) > 0)
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
                    @foreach($baseCourse->defaultCourseTranslation($languageCode)->files as $file)
                        <tr>
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
                                                        'courseTranslationId' => $baseCourse->defaultCourseTranslation($languageCode)->id
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
