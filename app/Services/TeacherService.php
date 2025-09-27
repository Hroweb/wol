<?php

namespace App\Services;

use App\Models\Teacher;
use App\Repositories\TeacherRepository;
use Illuminate\Http\UploadedFile;


class TeacherService
{
    public function __construct(private TeacherRepository $repo) {}

    public function list(int $perPage = 10, ?string $locale = null, $order=[]): \Illuminate\Pagination\LengthAwarePaginator
    {
        [$loc, $fallback] = $this->resolveLocale($locale);
        $paginator = $this->repo->paginateWithTranslations($perPage, $fallback, $loc, $order);
        $paginator->getCollection()->transform(function (Teacher $t) use ($loc, $fallback) {
            $this->attachLocalized($t, $loc, $fallback);
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

    private function resolveLocale(?string $locale): array
    {
        return [$locale ?? app()->getLocale(), config('app.fallback_locale')];
    }

    private function attachLocalized(Teacher $teacher, string $locale, string $fallback): void
    {
        $translation = $teacher->translations()->firstWhere('locale', $locale)
            ?? $teacher->translations()->firstWhere('locale', $fallback);

        $teacher->localized = [
            'first_name' => $translation->first_name ?? '',
            'last_name' => $translation->last_name ?? '',
            'position' => $translation->position ?? '',
            'church_name' => $translation->church_name ?? '',
            'bio' => $translation?->bio ?? '',
            'country' => $translation?->country ?? '',
            'city' => $translation?->city ?? '',
            'specializations' => explode(',', $translation?->specializations) ?? [],
            //'specializations' => (array) $translation?->specializations ?? [],
        ];
    }
}
