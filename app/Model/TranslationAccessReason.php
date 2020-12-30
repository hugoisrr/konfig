<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TranslationAccessReason extends Model
{
    /**
     * HasOne Relation to @see \App\Model\Language
     *
     * @return HasOne
     */
    public function language(): HasOne
    {
        return $this->hasOne(Language::class);
    }

    /**
     * BalongsTo Relation to @see \App\Model\AccessReason
     *
     * @return BelongsTo
     */
    public function accessReason(): BelongsTo
    {
        return $this->belongsTo(AccessReason::class);
    }
}
