<?php

namespace App\Http\Resources;

use App\Model\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
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
            $arrayBaseCourseVersion = [
                'version' => $this->defaultCourseTranslation($request->get('lang', 'en'))->version ?? 1,
            ];
        } else {
            $arrayBaseCourseVersion = [
                'version' => $this->defaultCourseTranslation()->version ?? 1,
            ];
        }

        return $arrayBaseCourseVersion;
    }
}
