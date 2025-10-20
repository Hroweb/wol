<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LessonPart extends Model
{
    use HasFactory;
    protected $fillable = [
        'lesson_id','teacher_id','part_number','duration_minutes',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(LessonPartTranslation::class);
    }
}
