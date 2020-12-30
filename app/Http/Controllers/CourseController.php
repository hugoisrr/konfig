<?php

namespace App\Http\Controllers;

use App\Model\Course;
use App\Model\Language;
use App\Model\TranslationCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $courses = Course::where('type', '!=', 'Base')
            ->orderBy('type')
            ->orderBy('live', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(4);
        return view('courses.index')->with('courses', $courses);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        if (is_null($request->input('searchCourse'))){
            return self::index();
        }

        $search = $request->get('searchCourse');

        $courses = Course::whereHas('courseTranslations', function($query) use ($search){
            $query->where('title', 'like', '%'.$search.'%');
        })
            ->orWhere('id', 'like', '%'.$search.'%')
            ->paginate(4);

        return view('courses.index')->with('courses', $courses);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filterType(Request $request)
    {
        if ($request->input('filterType') == "all"){
            return self::index();
        }

        $filterType = $request->get('filterType');

        $courses = Course::where('type', '=', $filterType)->paginate(4);

        return view('courses.index')->with('courses', $courses);
    }

    public function filterStatus(Request $request)
    {
        if ($request->input('filterStatus') == "all"){
            return self::index();
        }

        $filterStatus = $request->get('filterStatus');

        $courses = Course::where([['type', '!=', 'Base'],
            ['live', '=', $filterStatus]])
            ->paginate(4);

        return view('courses.index')->with('courses', $courses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        if (is_null($request->input('title-english'))
            || is_null($request->input('description-english'))
            || is_null($request->input('title-german'))
            || is_null($request->input('description-german'))
            || is_null($request->input('title-french'))
            || is_null($request->input('description-french'))
            || is_null($request->input('title-russian'))
            || is_null($request->input('description-russian'))
            || is_null($request->input('title-spanish'))
            || is_null($request->input('description-spanish'))
            || is_null($request->input('title-portuguese'))
            || is_null($request->input('description-portuguese'))
        ){
            return redirect()->back()->with('error', 'Für alle Sprachen muss ein Titel und eine Beschreibung vorhanden sein.')->withInput();
        }

        if ($request->input('type') == 'Course'){
            $this->validate($request, [
                'iap_id_apple' => 'required',
                'iap_id_google' => 'required',
            ]);
        }

        // Create Course
        $course = new Course;
        $course->type = $request->input('type');
        $course->iap_id_apple = $request->input('iap_id_apple');
        $course->iap_id_google = $request->input('iap_id_google');
        $course->save();

        // Create Course dir
        $path = '/public/courses/'.$course->id;
        Storage::makeDirectory($path);

        // Create TranslationCourse English Language
        self::saveCourseTranslation(
            $request->input('title-english'),
            $request->input('description-english'),
            $course,
            self::getLanguage('en'));

        // Create TranslationCourse German Language
        self::saveCourseTranslation(
            $request->input('title-german'),
            $request->input('description-german'),
            $course,
            self::getLanguage('de'));

        // Create TranslationCourse French Language
        self::saveCourseTranslation(
            $request->input('title-french'),
            $request->input('description-french'),
            $course,
            self::getLanguage('fr'));

        // Create TranslationCourse Russian Language
        self::saveCourseTranslation(
            $request->input('title-russian'),
            $request->input('description-russian'),
            $course,
            self::getLanguage('ru'));

        // Create TranslationCourse Spanish Language
        self::saveCourseTranslation(
            $request->input('title-spanish'),
            $request->input('description-spanish'),
            $course,
            self::getLanguage('es'));

        // Create TranslationCourse Portuguese Language
        self::saveCourseTranslation(
            $request->input('title-portuguese'),
            $request->input('description-portuguese'),
            $course,
            self::getLanguage('pt'));

        return redirect(route('courses.index'))->with('success', 'Kurs erstellt');
    }

    /**
     * @param Course $course
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Course $course)
    {
        $singleCourse = Course::find($course->id);
        return view('courses.show')->with('course', $singleCourse);
    }

    public function showBaseCourse()
    {
        $baseCourse = Course::where('type', '=', 'Base')->first();
        return view('courses.show-base-course')->with('baseCourse', $baseCourse);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Course  $course
     */
    public function edit(Course $course)
    {
        $course = Course::find($course->id);
        return view('courses.edit')->with('course', $course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Course  $course
     */
    public function update(Request $request, $id)
    {
        if ($request->input('type') == 'Course'){
            $this->validate($request, [
                'title-english' => 'required',
                'description-english' => 'required',
                'title-german' => 'required',
                'description-german' => 'required',
                'title-french' => 'required',
                'description-french' => 'required',
                'title-russian' => 'required',
                'description-russian' => 'required',
                'title-spanish' => 'required',
                'description-spanish' => 'required',
                'title-portuguese' => 'required',
                'description-portuguese' => 'required',
                'type' => 'required',
                'iap_id_apple' => 'required',
                'iap_id_google' => 'required',
            ]);
        }else{
            $this->validate($request, [
                'title-english' => 'required',
                'description-english' => 'required',
                'title-german' => 'required',
                'description-german' => 'required',
                'title-french' => 'required',
                'description-french' => 'required',
                'title-russian' => 'required',
                'description-russian' => 'required',
                'title-spanish' => 'required',
                'description-spanish' => 'required',
                'title-portuguese' => 'required',
                'description-portuguese' => 'required',
                'type' => 'required',
            ]);
        }

        // Validate Live Courses
        $liveCourses = Course::where('live', true)->get();
        if (count($liveCourses) == 2 && $request->input('liveStatus') == '0'){
            return redirect()->back()->with('error', 'Es muss mindestens einen Live-Kurs geben.')->withInput();
        }

        // Find & Update Course
        $course = Course::find($id);
        $course->type = $request->input('type');
        $course->live = $request->input('liveStatus');
        $course->iap_id_apple = $request->input('iap_id_apple');
        $course->iap_id_google = $request->input('iap_id_google');
        $course->save();

        // find & Update TranslationCourse with english Language
        self::updateCourseTranslation(
            $request->input('title-english'),
            $request->input('description-english'),
            $course,
            self::getLanguage('en'));

        // find & Update TranslationCourse with german Language
        self::updateCourseTranslation(
            $request->input('title-german'),
            $request->input('description-german'),
            $course,
            self::getLanguage('de'));

        // find & Update TranslationCourse with french Language
        self::updateCourseTranslation(
            $request->input('title-french'),
            $request->input('description-french'),
            $course,
            self::getLanguage('fr'));

        // find & Update TranslationCourse with russian Language
        self::updateCourseTranslation(
            $request->input('title-russian'),
            $request->input('description-russian'),
            $course,
            self::getLanguage('ru'));

        // find & Update TranslationCourse with spanish Language
        self::updateCourseTranslation(
            $request->input('title-spanish'),
            $request->input('description-spanish'),
            $course,
            self::getLanguage('es'));

        // find & Update TranslationCourse with portuguese Language
        self::updateCourseTranslation(
            $request->input('title-portuguese'),
            $request->input('description-portuguese'),
            $course,
            self::getLanguage('pt'));

        return redirect(route('courses.show', ['course' => $course]))->with('success', 'Kurs aktualisiert');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $courses = Course::where('live', true)->get();

        // Find Course
        $course = Course::find($id);

        if (!$course->live){
            $path = '/public/courses/'.$course->id;
            $courseRemoved = Storage::deleteDirectory($path);
            if(!$courseRemoved)
            {
                $course->delete();
                return redirect(route('courses.index'));
            }
            $course->delete();
            return redirect(route('courses.index'));
        }elseif ($course->live && count($courses) > 2){
            $path = '/public/courses/'.$course->id;
            $courseRemoved = Storage::deleteDirectory($path);
            if(!$courseRemoved)
            {
                $course->delete();
                return redirect(route('courses.index'));
            }
            $course->delete();
            return redirect(route('courses.index'));
        }else{
            return redirect(route('courses.index'))->with('error', 'Der Kurs kann nicht gelöscht werden, es muss mindestens ein Live-Kurs vorhanden sein.');
        }
    }

    /**
     * Return a Language object by language code, default is english
     *
     * @param string $languageCode
     * @return mixed
     */
    public static function getLanguage(string $languageCode = 'en')
    {
        return Language::where('language_code',$languageCode)->first();
    }

    /**
     * @param $langTitle
     * @param $langDescription
     * @param Course $course
     * @param Language $language
     */
    public static function saveCourseTranslation(string $langTitle, string $langDescription, Course $course, Language $language): void
    {
        // Create Language dir and sex directories
        $languageDirPath = '/public/courses/'.$course->id.'/'.$language->language_code;
        $femaleDirPath = '/public/courses/'.$course->id.'/'.$language->language_code.'/f';
        $masculineDirPath = '/public/courses/'.$course->id.'/'.$language->language_code.'/m';
        Storage::makeDirectory($languageDirPath);
        Storage::makeDirectory($femaleDirPath);
        Storage::makeDirectory($masculineDirPath);

        $translationCourse = new TranslationCourse;
        $translationCourse->title = $langTitle;
        $translationCourse->description = $langDescription;
        $translationCourse->course_id = $course->id;
        $translationCourse->language_id = $language->id;

        $translationCourse->save();
    }

    /**
     * @param string $langTitle
     * @param string $langDescription
     * @param Course $course
     * @param Language $language
     */
    public static function updateCourseTranslation(string $langTitle, string $langDescription, Course $course, Language $language): void
    {
        TranslationCourse::updateOrCreate(
            [
                'course_id' => $course->id,
                'language_id' => $language->id
            ],
            [
                'title' => $langTitle,
                'description' => $langDescription
            ]
        );
    }
}
