<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\StudentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StudentService
{
    public function __construct(private StudentRepository $repo) {}

    public function list(
        int $perPage = 10,
        array $order = [],
        ?string $search = null
    ): LengthAwarePaginator {
        $paginator = $this->repo->paginate($perPage, $order, $search);

        // Decorate each user with a computed full_name (for blade convenience)
        $paginator->getCollection()->transform(function (User $u) {
            $u->full_name = trim(($u->first_name ?? '').' '.($u->last_name ?? ''));
            return $u;
        });

        return $paginator;
    }

    public function get(int $id): ?User
    {
        $u = $this->repo->find($id);
        if ($u) {
            $u->full_name = trim(($u->first_name ?? '').' '.($u->last_name ?? ''));
        }
        return $u;
    }
}
