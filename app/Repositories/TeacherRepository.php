<?php

namespace App\Repositories;

use App\Models\Teacher;

class TeacherRepository
{
    protected function translatableSortable(): array
    {
        return ['first_name', 'last_name', 'position', 'church_name', 'city', 'country'];
    }

    protected function baseSortable(): array
    {
        return ['email', 'created_at', 'id'];
    }

    public function paginateWithTranslations(
        int $perPage,
        string $locale,
        string $fallback,
        array $order
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $q = Teacher::query();
        // checking if sortable field is in translatable (relational) table
        $needsJoin = collect($order)->contains(function ($item) {
            $key = $item['key'] ?? '';
            return $key === 'name' || in_array($key, $this->translatableSortable(), true);
        });

        if($needsJoin) {
            // for locale
            $q->leftJoin('teacher_translations as t_loc', function ($join) use ($locale) {
              $join->on('t_loc.teacher_id', '=', 'teachers.id')->where('t_loc.locale', '=', $locale);
            } );
            // for fallback if locale is not provided
            $q->leftJoin('teacher_translations as t_fb', function ($join) use ($fallback) {
                $join->on('t_fb.teacher_id', '=', 'teachers.id')->where('t_fb.locale', '=', $fallback);
            });
        }

        $q->select('teachers.*');

        if(!empty($order)) {
            foreach ($order as $orderValue) {
                $key = strtolower($orderValue['key'] ?? '');
                $dir = strtolower($orderValue['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

                if ($key === 'name') {
                    // Compound: first_name + last_name (translated with fallback -> base)
                    $q->orderByRaw("
                    CONCAT(
                        COALESCE(t_loc.first_name, t_fb.first_name, ''),
                        ' ',
                        COALESCE(t_loc.last_name,  t_fb.last_name,  '')
                    ) {$dir}
                ");
                    continue;
                }

                if (in_array($key, $this->translatableSortable(), true)) {
                    // translated-only fields: coalesce locale -> fallback (no teachers.<col>)
                    $q->orderByRaw("COALESCE(t_loc.{$key}, t_fb.{$key}, '') {$dir}");
                    continue;
                }

                if (in_array($key, $this->baseSortable(), true)) {
                    $q->orderBy("teachers.{$key}", $dir);
                    continue;
                }

                // Fallback default
                $q->orderBy('teachers.created_at', 'desc');

                // Non-translatable (e.g. email, created_at, id...)
                $q->orderBy("teachers.{$key}", $dir);
            }
        }

        // Default order if nothing provided
        if (empty($order)) {
            $q->latest('teachers.created_at');
        }

        return $q->paginate($perPage)->withQueryString();

        /*return $this->queryWithTranslations($locale, $fallback)
            ->latest()
            ->paginate($perPage);*/
    }

    public function findWithTranslations(int $id, string $locale, string $fallback): ?Teacher
    {
        return $this->queryWithTranslations($locale, $fallback)
            ->find($id);
    }


    private function queryWithTranslations(string $locale, string $fallback): \Illuminate\Database\Eloquent\Builder
    {
        return Teacher::with(['translations' => function ($q) use ($locale, $fallback) {
            $q->whereIn('locale', [$locale, $fallback]);
        }]);
    }
}
