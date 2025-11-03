<?php

namespace App\Http\Controllers\Admin;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Page;
use App\Services\PageService;
use Illuminate\Http\Request;
use App\Http\Requests\StorePageRequest;

class PagesController extends Controller
{
    public function index(Request $request, PageService $service): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $allowedSorts = ['title', 'slug', 'order', 'is_published', 'created_at'];
        $order = Helper::sortableOrder($request->query('sort') ?? 'order', $request->query('dir') ?? 'ASC', $allowedSorts);

        $pages = $service->list(10, config('app.fallback_locale'), $order);
        return view('admin.pages.index', compact('pages'));
    }

    public function create(PageService $service): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $formData = $service->getFormData();
        return view('admin.pages.create', $formData);
    }

    public function store(StorePageRequest $request, PageService $service): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $page = $service->store($request->validated());

        if ($request->wantsJson()) {
            return response()->json($page, 201);
        }

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Page created successfully');
    }

    public function edit(PageService $service, Page $page): \Illuminate\Contracts\View\View
    {
        $page->load(['translations', 'sections.translations']);

        $formData = $service->getFormData();
        return view('admin.pages.edit', compact('page') + $formData);
    }

    public function update(UpdatePageRequest $request, PageService $service, Page $page): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $page = $service->update($page, $request->validated());

        if ($request->wantsJson()) {
            return response()->json($page->fresh(['translations', 'sections.translations']), 200);
        }

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Page updated successfully');
    }

    public function destroy(PageService $service, Page $page): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $service->delete($page);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Page deleted successfully'], 200);
        }

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully');
    }
}
