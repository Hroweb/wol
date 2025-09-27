<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherRequest;
use App\Models\Teacher;
use App\Services\TeacherService;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request, TeacherService $service): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        $allowedSorts = ['name', 'email', 'position', 'created_at', 'id'];
        $order = Helper::sortableOrder($request->query('sort'), $request->query('dir'), $allowedSorts);

        $teachers = $service->list(10, 'hy', $order);

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        return view('admin.teachers.create');
    }

    public function store(StoreTeacherRequest $request, TeacherService $service): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo');
        }

        $teacher = $service->store($validated);

        if ($request->wantsJson()) {
            return response()->json($teacher, 201);
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully');
    }

}
