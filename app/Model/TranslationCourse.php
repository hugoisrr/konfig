<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TranslationCourse extends Model
{
    /**
     * @var array
     */
    protected $guarded = array();

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
     * BelongsTo Relation to @see \App\Model\Course
     *
     * @return BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * HasMany Relation to @see \App\Model\File
     *
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
