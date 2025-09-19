<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Church Management System') }}</title>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body
    x-data="{ page: 'admin', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark bg-gray-900': darkMode === true}"
>
{{--Page preload--}}
<x-admin.preloader />
<div class="flex h-screen overflow-hidden">
    {{--Aside--}}
    <x-admin.aside />
    <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
        <x-admin.header />
        {{--Main content--}}
        <main>
            <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                {{$slot}}
            </div>
        </main>
    </div>
</div>
</body>
</html>
