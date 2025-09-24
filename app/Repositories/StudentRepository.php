<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StudentRepository
{
    /** Which base columns we allow to sort by (no translations) */
    protected function baseSortable(): array
    {
        return ['email', 'created_at', 'id'];
    }

    /**
     * Paginate students with optional sorting and search.
     *
     * @param  int   $perPage
     * @param  array $order   e.g. [['key'=>'name','dir'=>'asc'], ['key'=>'email','dir'=>'desc']]
     * @param  string|null $search  (optional) search in name/email
     */
    public function paginate(
        int $perPage = 10,
        array $order = [],
        ?string $search = null
    ): LengthAwarePaginator {
        $q = User::query()
            ->where('role', 'student');

        // Optional quick search by name/email
        if ($search) {
            $like = '%'.trim($search).'%';
            $q->where(function ($w) use ($like) {
                $w->where('first_name', 'like', $like)
                    ->orWhere('last_name', 'like', $like)
                    ->orWhereRaw("CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,'')) LIKE ?", [$like])
                    ->orWhere('email', 'like', $like);
            });
        }

        // Always select users.*
        $q->select('users.*');

        // Apply order clauses
        foreach ($order as $o) {
            $key = strtolower($o['key'] ?? '');
            $dir = strtolower($o['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

            if ($key === 'name') {
                // Sort by full name (DB-level)
                $q->orderByRaw("
                    CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,'')) {$dir}
                ");
            } elseif (in_array($key, $this->baseSortable(), true)) {
                $q->orderBy("users.{$key}", $dir);
            }
        }

        // Default order if none provided
        if (empty($order)) {
            $q->latest('users.created_at');
        }

        return $q->paginate($perPage)->withQueryString();
    }

    /** Find a single student by id (optional helper) */
    public function find(int $id): ?User
    {
        return User::where('role', 'student')->find($id);
    }
}
