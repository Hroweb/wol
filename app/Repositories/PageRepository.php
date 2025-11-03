<?php

namespace App\Repositories;

use App\Models\Page;

class PageRepository
{
    protected function translatableSortable(): array
    {
        return ['title'];
    }

    protected function baseSortable(): array
    {
        return ['created_at', 'order', 'slug'];
    }

    public function paginateWithTranslations(
        int $perPage,
        string $locale,
        string $fallback,
        array $order
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $q = Page::query();
        $needsJoin = collect($order)->contains(function ($item) {
            $key = $item['key'] ?? '';
            return in_array($key, $this->translatableSortable(), true);
        });

        if($needsJoin) {
            // for locale
            $q->leftJoin('page_translations as p_loc', function ($join) use ($locale) {
                $join->on('p_loc.page_id', '=', 'pages.id')->where('p_loc.locale', '=', $locale);
            } );
            // for fallback if locale is not provided
            $q->leftJoin('page_translations as p_fb', function ($join) use ($fallback) {
                $join->on('p_fb.page_id', '=', 'pages.id')->where('p_fb.locale', '=', $fallback);
            });
        }

        $q->select('pages.*');

        if(!empty($order)) {
            foreach ($order as $orderValue) {
                $key = strtolower($orderValue['key'] ?? '');
                $dir = strtolower($orderValue['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

                if (in_array($key, $this->translatableSortable(), true)) {
                    $q->orderByRaw("COALESCE(p_loc.{$key}, p_fb.{$key}, '') {$dir}");
                    continue;
                }

                if (in_array($key, $this->baseSortable(), true)) {
                    $q->orderBy("pages.{$key}", $dir);
                    continue;
                }

                // Non-translatable
                $q->orderBy("pages.{$key}", $dir);
            }
        }

        // Default order if nothing provided
        if (empty($order)) {
            $q->orderBy('pages.order')->latest('pages.created_at');
        }

        return $q->paginate($perPage)->withQueryString();
    }
}
