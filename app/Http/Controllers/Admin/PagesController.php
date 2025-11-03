<?php

namespace App\Http\Controllers\Admin;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\PageService;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(Request $request, PageService $service): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $allowedSorts = ['title', 'slug', 'order', 'is_published', 'created_at'];
        $order = Helper::sortableOrder($request->query('sort') ?? 'order', $request->query('dir') ?? 'ASC', $allowedSorts);

        $pages = $service->list(10, config('app.fallback_locale'), $order);
        return view('admin.pages.index', compact('pages'));
    }
}
