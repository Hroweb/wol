<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index(Request $request, CourseService $service): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $allowedSorts = ['name', 'email', 'position', 'created_at', 'id'];
        $order = Helper::sortableOrder($request->query('sort'), $request->query('dir'), $allowedSorts);

        $courses = $service->list(10, 'en', $order);
        return view('admin.courses.index', compact('courses'));
    }
}
