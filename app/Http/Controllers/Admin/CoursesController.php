<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index(Request $request, CourseService $service): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $allowedSorts = ['title', 'slug', 'description', 'start_date', 'end_date', 'id'];
        $order = Helper::sortableOrder($request->query('sort'), $request->query('dir'), $allowedSorts);

        $courses = $service->list(10, 'hy', $order);
        return view('admin.courses.index', compact('courses'));
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('admin.courses.create');
    }

    public function store(StoreCourseRequest $request, CourseService $service): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $course = $service->store($request->validated());

        if ($request->wantsJson()) {
            return response()->json($course, 201);
        }

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully');
    }

    public function edit(Course $course): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(StoreCourseRequest $request, CourseService $service, Course $course): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $service->update($course, $request->validated());

        if ($request->wantsJson()) {
            return response()->json($course->fresh('translations'), 200);
        }

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully');
    }

    public function destroy(Course $course): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $course->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Course deleted successfully'], 200);
        }

        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully');
    }
}
