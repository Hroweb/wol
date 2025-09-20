<?php

namespace App\Helpers;

use Illuminate\Support\Facades\View;

class Helper
{
    public static function getAuthFullName($data): string
    {
        //return "{$data['first_name']} {$data['last_name']}" ?? '';
        return 'Admin';
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
                'uri' => 'lola',
//                'uri' => route('admin.teachers.index'),
                'route_name' => 'admin.teachers.index',
            ],
            [
                'name' => 'Lessons',
                'icon' => 'lessons-icon',
                'uri' => 'lessons',
                'route_name' => 'admin.lessons.index',
            ],
            [
                'name' => 'Students',
                'icon' => 'students-icon',
                'uri' => 'students',
                'route_name' => 'admin.students.index',
            ],
            [
                'name' => 'Announcements',
                'icon' => 'announcements-icon',
                'uri' => 'announcements',
                'route_name' => 'admin.announcements.index',
            ],
            [
                'name' => 'News',
                'icon' => 'news-icon',
                'uri' => 'news',
                'route_name' => 'admin.news.index',
            ]
        ];

        // Filter only existing icons
        foreach ($items as &$item) {
            $viewPath = 'components.dashboard.svgs.' . $item['icon'];
            if (!View::exists($viewPath)) {
                $item['icon'] = null;
            }
        }

        return $items;
    }
}
