<?php

namespace App\Services;

use App\Models\Teacher;
use App\Repositories\TeacherRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Traits\LocalizedServiceTrait;


class TeacherService
{
    use LocalizedServiceTrait;
    public function __construct(private readonly TeacherRepository $repo) {}

    public function list(int $perPage = 10, ?string $locale = null, $order=[]): \Illuminate\Pagination\LengthAwarePaginator
    {
        [$loc, $fallback] = $this->resolveLocale($locale);
        $paginator = $this->repo->paginateWithTranslations($perPage, $fallback, $loc, $order);
        $paginator->through(function (Teacher $t) use ($loc, $fallback) {
            $this->attachLocalized($t, $loc, $fallback, ['first_name', 'last_name', 'position', 'church_name', 'city', 'country', 'bio', 'specializations']);
            return $t;
        });
        return $paginator;
    }

    public function store(array $payload): Teacher
    {
        $photoPath = null;
        if (isset($payload['photo']) && $payload['photo'] instanceof UploadedFile) {
            $photoPath = $payload['photo']->store('teachers', 'public');
        }

        $base = [
            'photo' => $photoPath,
            'email' => $payload['email'] ?? null,
            'is_featured' => (bool)($payload['is_featured'] ?? false),
            'social_ig' => $payload['social_ig'] ?? null,
            'social_youtube' => $payload['social_youtube'] ?? null,
        ];

        $translations = $payload['translations'] ?? [];
        return $this->repo->createWithTranslations($base, $translations);
    }

    public function update(Teacher $teacher, array $payload): Teacher
    {
        $photoPath = $teacher->photo;
        if (isset($payload['photo']) && $payload['photo'] instanceof UploadedFile) {
            // Delete old photo if exists
            if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $photoPath = $payload['photo']->store('teachers', 'public');
        }

        $base = [
            'photo' => $photoPath,
            'email' => $payload['email'] ?? null,
            'is_featured' => (bool)($payload['is_featured'] ?? false),
            'social_ig' => $payload['social_ig'] ?? null,
            'social_youtube' => $payload['social_youtube'] ?? null,
        ];

        $translations = $payload['translations'] ?? [];
        return $this->repo->updateWithTranslations($teacher, $base, $translations);
    }
}
