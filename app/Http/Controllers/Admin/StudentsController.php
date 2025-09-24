<?php
// app/Http/Controllers/Admin/StudentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
