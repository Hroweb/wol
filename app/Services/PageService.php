<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Teacher;
use App\Repositories\PageRepository;
use App\Traits\LocalizedServiceTrait;
use Illuminate\Support\Facades\DB;

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
        $sections = $payload['sections'] ?? [];

        return DB::transaction(function () use ($base, $translations, $sections) {
            $page = $this->repo->createWithTranslations($base, $translations);

            // Handle sections if provided
            if (!empty($sections)) {
                $this->syncSections($page, $sections);
            }

            return $page->fresh(['translations', 'sections.translations']);
        });
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
        $sections = $payload['sections'] ?? [];
        $deletedSections = $payload['deleted_sections'] ?? [];

        return DB::transaction(function () use ($page, $base, $translations, $sections, $deletedSections) {
            $page = $this->repo->updateWithTranslations($page, $base, $translations);

            // Handle deleted sections first
            if (!empty($deletedSections) && is_array($deletedSections)) {
                foreach ($deletedSections as $sectionId) {
                    $section = $page->sections()->find($sectionId);
                    if ($section) {
                        $this->deleteSection($section);
                    }
                }
            }

            // Handle sections if provided
            if (!empty($sections)) {
                $this->syncSections($page, $sections);
            }

            return $page->fresh(['translations', 'sections.translations']);
        });
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

    /**
     * Sync page sections (create new, update existing, delete removed)
     */
    private function syncSections(Page $page, array $sections): void
    {
        // Get existing sections before any modifications
        $existingSections = $page->sections()->with('translations')->get()->keyBy('id');
        $existingSectionIds = $existingSections->keys()->toArray();

        // If sections array is empty, delete all existing sections
        if (empty($sections)) {
            $page->sections()->delete();
            return;
        }

        // Track which existing section IDs are being kept (updated)
        $keptSectionIds = [];

        foreach ($sections as $sectionData) {
            $sectionId = $sectionData['id'] ?? null;

            if ($sectionId && $existingSections->has($sectionId)) {
                // Update existing section
                $section = $existingSections[$sectionId];
                $section->update([
                    'section_type' => $sectionData['section_type'] ?? $section->section_type,
                    'order' => $sectionData['order'] ?? $section->order,
                    'is_active' => isset($sectionData['is_active']) ? (bool)$sectionData['is_active'] : $section->is_active,
                    'settings' => $sectionData['settings'] ?? $section->settings,
                ]);

                // Sync translations
                $this->syncSectionTranslations($section, $sectionData['translations'] ?? []);

                // Track that this section is being kept
                $keptSectionIds[] = $sectionId;
            } else {
                // Create new section
                $section = $page->sections()->create([
                    'section_type' => $sectionData['section_type'] ?? 'hero',
                    'order' => $sectionData['order'] ?? 0,
                    'is_active' => isset($sectionData['is_active']) ? (bool)$sectionData['is_active'] : true,
                    'settings' => $sectionData['settings'] ?? [],
                ]);

                // Create translations
                $this->syncSectionTranslations($section, $sectionData['translations'] ?? []);
            }
        }

        // Delete existing sections that were not kept (removed from form)
        // Only delete sections that existed before we started syncing
        $sectionsToDelete = array_diff($existingSectionIds, $keptSectionIds);
        if (!empty($sectionsToDelete)) {
            $page->sections()->whereIn('id', $sectionsToDelete)->delete();
        }
    }

    /**
     * Sync section translations (replace all translations)
     */
    private function syncSectionTranslations(PageSection $section, array $translations): void
    {
        // Delete existing translations
        $section->translations()->delete();

        // Create new translations
        foreach ($translations as $translationData) {
            $section->translations()->create([
                'locale' => $translationData['locale'] ?? config('app.fallback_locale'),
                'title' => $translationData['title'] ?? null,
                'subtitle' => $translationData['subtitle'] ?? null,
                'content' => $translationData['content'] ?? null,
                'cta_text' => $translationData['cta_text'] ?? null,
                'cta_link' => $translationData['cta_link'] ?? null,
            ]);
        }
    }

    /**
     * Delete a section and its translations
     */
    private function deleteSection(PageSection $section): bool
    {
        return $section->delete();
    }
}
