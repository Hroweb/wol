<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository
{
    protected function translatableSortable(): array
    {
        return [];
    }

    protected function baseSortable(): array
    {
        return [];
    }

    public function paginateWithTranslations(
        int $perPage,
        string $locale,
        string $fallback,
        array $order
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $q = Course::query();
        $needsJoin = collect($order)->contains(function ($item) {
            $key = $item['key'] ?? '';
            return in_array($key, $this->translatableSortable(), true);
        });

        if($needsJoin) {
            // for locale
            $q->leftJoin('course_translations as c_loc', function ($join) use ($locale) {
                $join->on('c_loc.course_id', '=', 'courses.id')->where('c_loc.locale', '=', $locale);
            } );
            // for fallback if locale is not provided
            $q->leftJoin('course_translations as c_fb', function ($join) use ($fallback) {
                $join->on('c_fb.course_id', '=', 'courses.id')->where('c_fb.locale', '=', $fallback);
            });
        }

        $q->select('courses.*');

        if(!empty($order)) {
            foreach ($order as $orderValue) {
                $key = strtolower($orderValue['key'] ?? '');
                $dir = strtolower($orderValue['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

                if (in_array($key, $this->translatableSortable(), true)) {
                    // translated-only fields: coalesce locale -> fallback (no teachers.<col>)
                    $q->orderByRaw("COALESCE(t_loc.{$key}, t_fb.{$key}, '') {$dir}");
                    continue;
                }

                if (in_array($key, $this->baseSortable(), true)) {
                    $q->orderBy("courses.{$key}", $dir);
                    continue;
                }

                // Fallback default
                $q->orderBy('courses.created_at', 'desc');

                // Non-translatable (e.g. email, created_at, id...)
                $q->orderBy("courses.{$key}", $dir);
            }
        }

        // Default order if nothing provided
        if (empty($order)) {
            $q->latest('courses.created_at');
        }

        return $q->paginate($perPage)->withQueryString();
    }

    private function queryWithTranslations(string $locale, string $fallback): \Illuminate\Database\Eloquent\Builder
    {
        return Course::with(['translations' => function ($q) use ($locale, $fallback) {
            $q->whereIn('locale', [$locale, $fallback]);
        }]);
    }
}
