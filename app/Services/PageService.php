<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Page;
use App\Models\Teacher;
use App\Repositories\PageRepository;
use App\Traits\LocalizedServiceTrait;

class PageService
{
    use LocalizedServiceTrait;
    public function __construct(private readonly PageRepository $repo){}

    public function list(int $perPage = 10, ?string $locale = null, $order = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        [$loc, $fallback] = $this->resolveLocale($locale);
        $paginator = $this->repo->paginateWithTranslations($perPage, $fallback, $loc, $order);
        $paginator->through(function (Page $page) use ($loc, $fallback) {
            $this->attachLocalized($page, $loc, $fallback, ['title', 'meta_title', 'meta_description']);
            return $page;
        });
        return $paginator;
    }

    public function store(array $payload): Page
    {
        $base = [
            'slug' => $payload['slug'],
            'template' => $payload['template'] ?? 'default',
            'is_published' => $payload['is_published'] ?? false,
            'order' => $payload['order'] ?? 0,
        ];
        $translations = $payload['translations'] ?? [];
        return $this->repo->createWithTranslations($base, $translations);
    }

    public function update(Page $page, array $payload): Page
    {
        $base = [
            'slug' => $payload['slug'] ?? $page->slug,
            'template' => $payload['template'] ?? $page->template,
            'is_published' => isset($payload['is_published']) ? (bool)$payload['is_published'] : $page->is_published,
            'order' => $payload['order'] ?? $page->order,
        ];
        $translations = $payload['translations'] ?? [];
        $page = $this->repo->updateWithTranslations($page, $base, $translations);

        // Handle sections if provided
        if (isset($payload['sections'])) {
            //$this->syncSections($page, $payload['sections']);
        }

        // Handle deleted sections
        /*if (isset($payload['deleted_sections']) && is_array($payload['deleted_sections'])) {
            foreach ($payload['deleted_sections'] as $sectionId) {
                $section = $page->sections()->find($sectionId);
                if ($section) {
                    $this->deleteSection($section);
                }
            }
        }*/

        return $page->fresh(['translations'/*, 'sections.translations'*/]);
    }

    public function delete(Page $page): bool
    {
        return $page->delete();
    }


    public function getFormData(): array
    {
        return [
            'teachers' => $this->getFeaturedTeachers(),
            'courses' => $this->getCourses(),
        ];
    }

    /**
     * Get featured teachers formatted for dropdown
     */
    private function getFeaturedTeachers(): \Illuminate\Support\Collection
    {
        $fallback = config('app.fallback_locale');

        return Teacher::where('is_featured', true)
            ->with('translations')
            ->get()
            ->map(function($teacher) use ($fallback) {
                $trans = $teacher->t($fallback);
                return [
                    'id' => $teacher->id,
                    'name' => $trans ? trim($trans->first_name . ' ' . $trans->last_name) : 'Teacher #' . $teacher->id,
                ];
            });
    }

    /**
     * Get courses formatted for dropdown
     */
    private function getCourses(): \Illuminate\Support\Collection
    {
        $fallback = config('app.fallback_locale');

        return Course::with('translations')
            ->get()
            ->map(function($course) use ($fallback) {
                $trans = $course->t($fallback);
                return [
                    'id' => $course->id,
                    'title' => $trans?->title ?? 'Course #' . $course->id,
                ];
            });
    }
}
