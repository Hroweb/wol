<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'teacher_id',
        'locale',
        'first_name',
        'last_name',
        'bio',
        'specializations',
        'position',
        'church_name',
        'city',
        'country',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
