<?php

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CrudRepositoryInterface
{
    /**
     * Paginate entities with translations
     */
    public function paginateWithTranslations(
        int $perPage,
        string $locale,
        string $fallback,
        array $order
    ): LengthAwarePaginator;

    /**
     * Create entity with translations
     */
    public function createWithTranslations(array $baseData, array $translations);

    /**
     * Update entity with translations
     */
    public function updateWithTranslations($model, array $baseData, array $translations);

    /**
     * Find entity by ID
     */
    public function find(int $id);

    /**
     * Delete entity
     */
    public function delete($model): bool;
}
