<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    /**
     * HasMany Relation to @see \App\Model\TranslationCourse
     *
     * @return HasMany
     */
    public function courseTranslations(): HasMany
    {
        return $this->hasMany(TranslationCourse::class);
    }

    /**
     * HasMany Relation to @see \App\Model\TranslationAccessReason
     *
     * @return HasMany
     */
    public function accessReasonTranslations(): HasMany
    {
        return $this->hasMany(TranslationAccessReason::class);
    }
}
