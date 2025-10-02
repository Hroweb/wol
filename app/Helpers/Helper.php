<?php

namespace App\Helpers;

use Illuminate\Support\Facades\View;

class Helper
{
    public static function getAuthFullName($data): string
    {
        return "{$data['first_name']} {$data['last_name']}" ?? '';
    }

    public static function convertDate($datetime): string
    {
        return date('d M, Y', strtotime($datetime));
    }

    public static function sortableOrder($sort, $dir, $fields): array
    {
        $sort = strtolower($sort ?? 'created_at'); // e.g. name | email | position | created_at
        $dir  = strtolower($dir ?? 'desc');       // asc | desc

        if (! in_array($sort, $fields, true)) $sort = 'created_at';
        if (! in_array($dir, ['asc','desc'], true)) $dir = 'desc';

        // Build order array for repo (can include multiple)
        return [
            ['key' => $sort, 'dir' => $dir],
        ];
    }

    public static function dashboardMenu(): array
    {
        $items = [
            [
                'name' => 'Dashboard',
                'icon' => 'dashboard-icon',
                'uri' => route('admin.dashboard'),
                'route_name' => 'admin.dashboard',
            ],
            [
                'name' => 'Teachers',
                'icon' => 'teachers-icon',
                'uri' => route('admin.teachers.index'),
                'route_name' => 'admin.teachers',
            ],
            [
                'name' => 'Courses',
                'icon' => 'lessons-icon',
                'uri' => route('admin.courses.index'),
                'route_name' => 'admin.courses',
            ],
            [
                'name' => 'Students',
                'icon' => 'students-icon',
                'uri' => route('admin.students.index'),
                'route_name' => 'admin.students',
            ],
            [
                'name' => 'Announcements',
                'icon' => 'announcements-icon',
                'uri' => 'announcements',
                'route_name' => 'admin.announcements',
            ],
            [
                'name' => 'News',
                'icon' => 'news-icon',
                'uri' => 'news',
                'route_name' => 'admin.news',
            ]
        ];

        // Filter only existing icons
        foreach ($items as &$item) {
            $viewPath = 'components.admin.svgs.' . $item['icon'];
            if (!View::exists($viewPath)) {
                $item['icon'] = null;
            }
        }

        return $items;
    }

    public static function getLocales(): array
    {
        return ['en' => 'English', 'hy' => 'Armenian'];
    }
}
