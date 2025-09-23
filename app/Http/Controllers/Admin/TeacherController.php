<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Services\TeacherService;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request, TeacherService $service): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        // Read sort inputs
        $sort = strtolower($request->query('sort', 'created_at')); // e.g. name | email | position | created_at
        $dir  = strtolower($request->query('dir',  'desc'));       // asc | desc

        // Whitelist (add more as needed)
        $allowedSorts = ['name', 'email', 'position', 'created_at', 'id'];
        if (! in_array($sort, $allowedSorts, true)) $sort = 'created_at';
        if (! in_array($dir, ['asc','desc'], true)) $dir = 'desc';

        // Build order array for repo (can include multiple)
        $order = [
            ['key' => $sort, 'dir' => $dir],
        ];

        $teachers = $service->list(10, 'en', $order);
        return view('admin.teachers.index', compact('teachers'));
    }
}
