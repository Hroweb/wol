<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonPartTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_part_id',
        'locale',
        'audio_file'
    ];

    public function lessonPart(): BelongsTo
    {
        return $this->belongsTo(LessonPart::class);
    }
}
