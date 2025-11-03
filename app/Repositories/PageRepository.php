<?php

namespace App\Repositories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PageRepository
{
    /* ===== Sortable columns ===== */

    protected function translatableSortable(): array
    {
        return ['title', 'content'];
    }

    protected function baseSortable(): array
    {
        return ['created_at', 'order', 'slug'];
    }

    /* ===== Public API ===== */

    public function paginateWithTranslations(
        int $perPage,
        string $locale,
        string $fallback,
        array $order
    ): LengthAwarePaginator {
        $q = Page::query();

        if ($this->shouldJoinTranslations($order)) {
            $this->applyTranslationJoins($q, $locale, $fallback);
        }

        $q->select('pages.*');

        if (!empty($order)) {
            $this->applyOrdering($q, $order);
        } else {
            // Default order if nothing provided
            $q->orderBy('pages.order')->latest('pages.created_at');
        }

        return $q->paginate($perPage)->withQueryString();
    }

    public function createWithTranslations(array $baseData, array $translations): Page
    {
        return DB::transaction(function () use ($baseData, $translations) {
            /** @var Page $page */
            $page = Page::create($baseData);

            $page->translations()->createMany(
                $this->prepareTranslations($translations)
            );

            return $page->load('translations');
        });
    }

    public function updateWithTranslations(Page $page, array $baseData, array $translations): Page
    {
        return DB::transaction(function () use ($page, $baseData, $translations) {
            $page->update($baseData);

            // Current behavior: wipe & re-insert
            $page->translations()->delete();

            $page->translations()->createMany(
                $this->prepareTranslations($translations)
            );

            return $page->load('translations');
        });
    }

    /* ===== Private helpers ===== */

    /**
     * Should we join translation tables for sorting?
     */
    private function shouldJoinTranslations(array $order): bool
    {
        return collect($order)->contains(function ($item) {
            $key = strtolower($item['key'] ?? '');
            return in_array($key, $this->translatableSortable(), true);
        });
    }

    /**
     * Join current-locale and fallback-locale translations for sorting.
     */
    private function applyTranslationJoins(Builder $q, string $locale, string $fallback): void
    {
        $q->leftJoin('page_translations as p_loc', function ($join) use ($locale) {
            $join->on('p_loc.page_id', '=', 'pages.id')
                ->where('p_loc.locale', '=', $locale);
        });

        $q->leftJoin('page_translations as p_fb', function ($join) use ($fallback) {
            $join->on('p_fb.page_id', '=', 'pages.id')
                ->where('p_fb.locale', '=', $fallback);
        });
    }

    /**
     * Apply ordering over base columns and translatable columns.
     * Expects joins to be present if translatable keys are requested.
     */
    private function applyOrdering(Builder $q, array $order): void
    {
        foreach ($order as $orderValue) {
            $key = strtolower($orderValue['key'] ?? '');
            $dir = strtolower($orderValue['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

            if (in_array($key, $this->translatableSortable(), true)) {
                // COALESCE current locale -> fallback -> empty
                $q->orderByRaw("COALESCE(p_loc.{$key}, p_fb.{$key}, '') {$dir}");
                continue;
            }

            if (in_array($key, $this->baseSortable(), true)) {
                $q->orderBy("pages.{$key}", $dir);
                continue;
            }

            // Unknown key: try ordering by pages.{key} as a last resort
            $q->orderBy("pages.{$key}", $dir);
        }
    }

    /**
     * Normalize incoming translations payload to the DB insert shape.
     */
    private function prepareTranslations(array $translations): array
    {
        $now = now();

        return collect($translations)->map(function ($t) use ($now) {
            return [
                'locale'           => $t['locale'],
                'title'            => $t['title'] ?? '',
                'meta_title'       => $t['meta_title'] ?? null,
                'meta_description' => $t['meta_description'] ?? null,
                'meta_keywords'    => $t['meta_keywords'] ?? null,
                'content'          => $t['content'] ?? null,
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        })->all();
    }
}
