<?php

namespace App\Services;

use App\Models\Page;
use App\Repositories\PageRepository;
use App\Traits\LocalizedServiceTrait;

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
}
