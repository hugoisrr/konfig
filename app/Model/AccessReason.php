<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccessReason extends Model
{
    /**
     * HasMany Relation to @see \App\Model\TranslationAccessReason
     *
     * @return HasMany
     */
    public function accessReasonTranslations(): HasMany
    {
        return $this->hasMany(TranslationAccessReason::class);
    }

    /**
     * @param string $languageCode
     * @return Model|HasMany|object|null
     */
    public function defaultAccessReasonTranslation(string $languageCode = 'en')
    {
        $defaultLang = Language::where('language_code', $languageCode)->first();

        return $this->hasMany(TranslationAccessReason::class)
            ->where('language_id', $defaultLang->id)
            ->first();
    }
}
