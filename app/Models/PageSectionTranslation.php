<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSectionTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_section_id',
        'locale',
        'title',
        'subtitle',
        'content',
        'cta_text',
        'cta_link',
    ];

    public function pageSection(): BelongsTo
    {
        return $this->belongsTo(PageSection::class);
    }
}
