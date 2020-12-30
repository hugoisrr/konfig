<?php

namespace App\Http\Controllers;

use App\Model\File;
use App\Model\TranslationCourse;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function uploadFile(Request $request)
    {
        $courseId = $request->input('courseId');
        $language = $request->input('language');
        $courseTranslationId = $request->input('courseTranslationId');

        $sex = $request->input('sex');
        if ($sex == 'female'){
            $this->validate($request, [
                'femaleFilesUpload' => 'required',
            ]);

            if ($request->hasFile('femaleFilesUpload')){
                foreach ($request->femaleFilesUpload as $femaleFile){
                    // Verify that file is audio file
                    try {
                        if ($femaleFile->getMimeType() != 'audio/mpeg'){
                            return redirect()->back()->with('error', 'Die Datei muss Audio sein.');
                        }
                    }catch (\InvalidArgumentException $exception){
                        return redirect()->back()->with('error', 'Die Datei muss Audio sein.');
                    }
                    // Store File
                    $fileName = $femaleFile->getClientOriginalName();
                    $path = $femaleFile->storeAs(
                        'public/courses/'.$courseId.'/'.$language.'/f',
                        $fileName);

                    // Create File
                    $femaleAudioFile = new File;
                    $femaleAudioFile->type = 'f-audio';
                    $femaleAudioFile->name = $fileName;
                    $femaleAudioFile->path = $path;
                    $femaleAudioFile->translation_course_id = $courseTranslationId;
                    $femaleAudioFile->save();
                }
            }

            // Increment version in TranslationCourse by one
            $this->incrementTranslationCourseVersion($courseTranslationId);

            return redirect()->back()->with('success', 'Datei wurde gespeichert.');
        }elseif ($sex == 'masculine'){
            $this->validate($request, [
                'maleFilesUpload' => 'required',
            ]);

            if ($request->hasFile('maleFilesUpload')){
                foreach ($request->maleFilesUpload as $maleFile){
                    // Verify that file is audio file
                    try {
                        if ($maleFile->getMimeType() != 'audio/mpeg'){
                           return redirect()->back()->with('error', 'Die Datei muss Audio sein.');
                       }
                    }catch (\InvalidArgumentException $exception){
                        return redirect()->back()->with('error', 'Die Datei muss Audio sein.');
                    }
                    // Store File
                    $fileName = $maleFile->getClientOriginalName();
                    $path = $maleFile->storeAs(
                        'public/courses/'.$courseId.'/'.$language.'/m',
                        $fileName);

                    // Create File
                    $maleAudioFile = new File;
                    $maleAudioFile->type = 'm-audio';
                    $maleAudioFile->name = $fileName;
                    $maleAudioFile->path = $path;
                    $maleAudioFile->translation_course_id = $courseTranslationId;
                    $maleAudioFile->save();
                }
            }

            // Increment version in TranslationCourse by one
            $this->incrementTranslationCourseVersion($courseTranslationId);

            return redirect()->back()->with('success', 'Datei wurde gespeichert.');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadXMLFile(Request $request): \Illuminate\Http\RedirectResponse
    {
        $courseId = $request->input('courseId');
        $language = $request->input('language');
        $courseTranslationId = $request->input('courseTranslationId');
        $file = $request->file('xmlFileUpload');

        $path = '/public/courses/'.$courseId.'/'.$language.'/tree.xml';
        $exists = Storage::exists($path);

        // Verify that only file 'tree.xml' exists
        if ($exists){
            return redirect()->back()->with('error', 'Die Datei tree.xml ist bereits vorhanden.');
        }

        // Verify that file is XML
        if ($file->getMimeType() != 'text/xml'){
            return redirect()->back()->with('error', 'Die Datei muss XML sein.');
        }

        // Stores xml file
        $storePath = Storage::putFileAs(
            '/public/courses/'.$courseId.'/'.$language,
            $file,
            'tree.xml');
        // Create File
        $xmlFile = new File;
        $xmlFile->type = 'tree';
        $xmlFile->name = $file->getClientOriginalName();
        $xmlFile->path = $storePath;
        $xmlFile->translation_course_id = $courseTranslationId;
        $xmlFile->save();

        // Increment version in TranslationCourse by one
        $this->incrementTranslationCourseVersion($courseTranslationId);

        return redirect()->back()->with('success', 'Datei wurde gespeichert');
    }

    /**
     * @param $courseTranslationId
     */
    private function incrementTranslationCourseVersion($courseTranslationId)
    {
        $translationCourse = TranslationCourse::find($courseTranslationId);
        $translationCourse->version = ++$translationCourse->version;
        $translationCourse->save();
    }

    /**
     * @param $id
     * @param $courseTranslationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteFile($id, $courseTranslationId): \Illuminate\Http\RedirectResponse
    {
        $file = File::find($id);
        $fileRemoved = Storage::delete($file->path);

        // Increment version in TranslationCourse by one
        $this->incrementTranslationCourseVersion($courseTranslationId);

        if(!$fileRemoved)
        {
            $file->delete();
            return redirect()->back();
        }
        $file->delete();

        return redirect()->back();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function downloadFile($id)
    {
        $file = File::find($id);

        return Storage::download($file->path);
    }
}
