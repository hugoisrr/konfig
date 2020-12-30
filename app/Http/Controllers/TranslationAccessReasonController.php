<?php

namespace App\Http\Controllers;

use App\Model\AccessReason;
use App\Model\Language;
use App\Model\TranslationAccessReason;
use Illuminate\Http\Request;

class TranslationAccessReasonController extends Controller
{
    /**
     * @param string $message
     * @param AccessReason $accessReason
     * @param Language $language
     */
    public static function storeAccessReasonTranslation(string $message, AccessReason $accessReason, Language $language): void
    {
        $translationAccessReason = new TranslationAccessReason;
        $translationAccessReason->message = $message;
        $translationAccessReason->access_reason_id = $accessReason->id;
        $translationAccessReason->language_id = $language->id;

        $translationAccessReason->save();
    }
}
