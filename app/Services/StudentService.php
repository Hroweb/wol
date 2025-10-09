<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\StudentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

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

    public function store(array $payload): User
    {
        $data = [
            'first_name' => $payload['first_name'],
            'last_name' => $payload['last_name'],
            'email' => $payload['email'],
            'password' => Hash::make($payload['password'] ?? 'password123'), // Default password if not provided
            'role' => 'student',
            'date_of_birth' => $payload['date_of_birth'] ?? null,
            'phone' => $payload['phone'] ?? null,
            'address' => $payload['address'] ?? null,
            'city' => $payload['city'] ?? null,
            'country' => $payload['country'] ?? null,
            'position' => $payload['position'] ?? null,
            'church_affiliation' => $payload['church_affiliation'] ?? null,
            'social_links' => $payload['social_links'] ?? null,
        ];

        return $this->repo->create($data);
    }

    public function update(User $student, array $payload): User
    {
        $data = [
            'first_name' => $payload['first_name'],
            'last_name' => $payload['last_name'],
            'email' => $payload['email'],
            'date_of_birth' => $payload['date_of_birth'] ?? null,
            'phone' => $payload['phone'] ?? null,
            'address' => $payload['address'] ?? null,
            'city' => $payload['city'] ?? null,
            'country' => $payload['country'] ?? null,
            'position' => $payload['position'] ?? null,
            'church_affiliation' => $payload['church_affiliation'] ?? null,
            'social_links' => $payload['social_links'] ?? null,
        ];

        // Only update password if provided
        if (!empty($payload['password'])) {
            $data['password'] = Hash::make($payload['password']);
        }

        return $this->repo->update($student, $data);
    }

    public function destroy(User $student): bool
    {
        return $this->repo->delete($student);
    }
}
