<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    protected $fillable = [
        'photo',
        'email',
        'is_featured',
        'social_ig',
        'social_youtube',
    ];

    protected $casts = ['is_featured' => 'boolean'];

    public function translations(): HasMany
    {
        return $this->hasMany(TeacherTranslation::class);
    }
}
