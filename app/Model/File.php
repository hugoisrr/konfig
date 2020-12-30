<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class File extends Model
{
    /**
     * HasOne relation course @see \App\Model\TranslationCourse
     *
     * @return HasOne
     */
    public function translationCourse(): HasOne
    {
        return $this->hasOne(TranslationCourse::class);
    }
}
