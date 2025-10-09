<?php

namespace App\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface CrudServiceInterface
{
    /**
     * List entities with localization
     */
    public function list(int $perPage = 10, ?string $locale = null, array $order = []): LengthAwarePaginator;

    /**
     * Create a new entity
     */
    public function store(array $payload);

    /**
     * Update an existing entity
     */
    public function update(Model $model, array $payload): Model;

    /**
     * Delete an entity
     */
//    public function delete($model): bool;

    /**
     * Get entity by ID
     */
//    public function get(int $id);
}
