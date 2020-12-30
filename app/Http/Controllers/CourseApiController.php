<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseCourseResource;
use App\Http\Resources\CourseCollection;
use App\Model\Course;
use Illuminate\Http\Request;
use Laravel\Passport\Token;
use Lcobucci\JWT\Parser;

class CourseApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('client');
    }

    /**
     * Returns a collection of courses, with the following constraints
     * - Base Courses are excluded
     * - With a production client id, returns only live courses
     * - For a development client id, return all courses
     * - The courses are display by the most
     * - Default language is English
     * - To query for a translation an attribute in request body "lang" is needed
     * - Format of the language must be in ISO 639-1, e.g. "en"
     * @param Request $request
     * @return CourseCollection
     */
    public function index(Request $request)
    {
        // Get Client Id from the Token's head
        $client = CourseApiController::getClientFromToken($request);

        if ($client->id == getenv('PASSPORT_ANDROID_ACCESS_CLIENT_ID')
            || $client->id == getenv('PASSPORT_IOS_ACCESS_CLIENT_ID'))
        {
            $courses = Course::where([['type', '!=', 'Base'], ['live', true]])
                ->orderBy('type')
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($client->id == getenv('PASSPORT_DEV_ANDROID_ACCESS_CLIENT_ID')
            || $client->id == getenv('PASSPORT__DEV_IOS_ACCESS_CLIENT_ID'))
        {
            $courses = Course::where('type', '!=', 'Base')
                ->orderBy('type')
                ->orderBy('live', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $courses = [];
        }

        return new CourseCollection($courses);
    }

    /**
     * Returns the 'version' attribute of the BASE course
     * - Default language is English
     * - To query for a translation an attribute in request body "lang" is needed
     * - Format of the language must be in ISO 639-1, e.g. "en"
     * @param Request $request
     * @return BaseCourseResource
     */
    public function baseCourse(Request $request)
    {
        return new BaseCourseResource(Course::where('type', '=', 'Base')->first());
    }

    /**
     * Returns an auth client from the Token's head
     * @param Request $request
     * @return mixed
     */
    public static function getClientFromToken(Request $request)
    {
        $bearerToken = request()->bearerToken();
        $tokenId = (new Parser())->parse($bearerToken)->getClaim('jti');
        return Token::find($tokenId)->client;
    }

}
