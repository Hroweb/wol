<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Services\TeacherService;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request, TeacherService $service): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        $allowedSorts = ['name', 'email', 'position', 'created_at', 'id'];
        $order = Helper::sortableOrder($request->query('sort'), $request->query('dir'), $allowedSorts);

        $teachers = $service->list(10, 'en', $order);
        return view('admin.teachers.index', compact('teachers'));
    }
}
