<?php
// app/Http/Controllers/Admin/StudentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Models\User;
use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function index(Request $request, StudentService $service): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        // Read query params
        $sort = strtolower($request->query('sort', 'created_at')); // name|email|created_at|id
        $dir  = strtolower($request->query('dir',  'desc'));       // asc|desc
        $q    = $request->query('q');                               // optional search

        // Whitelist + sanitize
        $allowedSorts = ['name','email','created_at','id'];
        if (! in_array($sort, $allowedSorts, true)) $sort = 'created_at';
        if (! in_array($dir, ['asc','desc'], true)) $dir = 'desc';

        $order = [
            ['key' => $sort, 'dir' => $dir],
        ];

        $students = $service->list(
            perPage: 10,
            order: $order,
            search: $q
        );

        return view('admin.students.index', compact('students', 'q', 'sort', 'dir'));
    }

    /* Create */
    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        return view('admin.students.create');
    }

    public function store(StoreStudentRequest $request, StudentService $service): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $student = $service->store($validated);

        if ($request->wantsJson()) {
            return response()->json($student, 201);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully');
    }

    /* Edit */
    public function edit(User $student): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(StoreStudentRequest $request, StudentService $service, User $student): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $service->update($student, $validated);

        if ($request->wantsJson()) {
            return response()->json($student->fresh(), 200);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully');
    }

    public function destroy(User $student): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $student->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Student deleted successfully'], 200);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully');
    }
}
