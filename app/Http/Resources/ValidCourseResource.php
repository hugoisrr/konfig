<?php

namespace App\Http\Resources;

use App\Model\AccessReason;
use App\Model\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class ValidCourseResource extends JsonResource
{
    // Access Reason Statuses
    public const ACCESS_REASON_STATUS_TEST_COURSE          = 0;
    public const ACCESS_REASON_STATUS_VALID_SUBSCRIPTION   = 15;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $arrayValidCourseData = [
            'id' => $this->id,
        ];
        // Validates if Language is found or not. Default Language is English
        $language = Language::where('language_code', $request->input('lang'))->first();
        if (!is_null($language))
        {
            if ($this->type == 'Test')
            {
                $accessReason = AccessReason::where('status', self::ACCESS_REASON_STATUS_TEST_COURSE)->first();
                $arrayValidCourseData['status'] = $accessReason->status;
                $arrayValidCourseData['message'] = $accessReason->defaultAccessReasonTranslation($request->get('lang', 'en'))->message;
            } elseif ($this->type == 'Course'){
                $accessReason = AccessReason::where('status', self::ACCESS_REASON_STATUS_VALID_SUBSCRIPTION)->first();
                $arrayValidCourseData['status'] = $accessReason->status;
                $arrayValidCourseData['message'] = $accessReason->defaultAccessReasonTranslation($request->get('lang', 'en'))->message;
            }
        } else {
            if ($this->type == 'Test')
            {
                $accessReason = AccessReason::where('status', self::ACCESS_REASON_STATUS_TEST_COURSE)->first();
                $arrayValidCourseData['status'] = $accessReason->status;
                $arrayValidCourseData['message'] = $accessReason->defaultAccessReasonTranslation()->message;
            } elseif ($this->type == 'Course'){
                $accessReason = AccessReason::where('status', self::ACCESS_REASON_STATUS_VALID_SUBSCRIPTION)->first();
                $arrayValidCourseData['status'] = $accessReason->status;
                $arrayValidCourseData['message'] = $accessReason->defaultAccessReasonTranslation()->message;
            }
        }

        return $arrayValidCourseData;
    }
}
