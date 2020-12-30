<?php

namespace App\Http\Resources;

use App\Http\Controllers\CourseApiController;
use App\Model\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Validates the language_code, if not valid returns by default
     * Courses in English Language, otherwise return Courses by the
     * Language requested.
     *
     * Then it gets the client's Id from the Token. Depending of the
     * client's Id (Platform) returns its corresponding 'iap_id' attribute.
     *
     * If the type of Course is 'Test' it doesn't return the attribute 'iap_id'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Validates if Language is found or not. Default Language is English
        $language = Language::where('language_code', $request->input('lang'))->first();
        if (!is_null($language))
        {
            $arrayCourseData = [
                'id' => $this->id,
                'version' => $this->defaultCourseTranslation($request->get('lang', 'en'))->version ?? 1,
                'type' => $this->type,
                'title' => $this->defaultCourseTranslation($request->get('lang', 'en'))->title ?? 'UNSET',
                'description' => $this->defaultCourseTranslation($request->get('lang', 'en'))->description ?? 'UNSET',
            ];
        } else {
            $arrayCourseData = [
                'id' => $this->id,
                'version' => $this->defaultCourseTranslation()->version ?? 1,
                'type' => $this->type,
                'title' => $this->defaultCourseTranslation()->title ?? 'UNSET',
                'description' => $this->defaultCourseTranslation()->description ?? 'UNSET',
            ];
        }

        // Get Client Id from the Token's head
        $client = CourseApiController::getClientFromToken($request);

        if (($client->id == getenv('PASSPORT_ANDROID_ACCESS_CLIENT_ID')
                || $client->id == getenv('PASSPORT_DEV_ANDROID_ACCESS_CLIENT_ID'))
            && ($this->type != 'Test'))
        {
            $arrayCourseData['iap_id'] = $this->iap_id_google;
        } elseif (($client->id == getenv('PASSPORT_IOS_ACCESS_CLIENT_ID')
                || $client->id == getenv('PASSPORT__DEV_IOS_ACCESS_CLIENT_ID'))
            && ($this->type != 'Test'))
        {
            $arrayCourseData['iap_id'] = $this->iap_id_apple;
        }

        return $arrayCourseData;
    }
}
