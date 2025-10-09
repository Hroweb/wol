<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Student') }}
        </h2>
    </x-slot>

    <div class="customers-page admin-page">
        {{-- Breadcrumbs --}}
        <x-admin.breadcrumbs page="Students / Edit" />
        <div class="space-y-5 sm:space-y-6">
            <form action="{{ route('admin.students.update', $student) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="course-edit-layout">
                    {{-- Left Sidebar: Basic Fields --}}
                    <div class="course-main-content space-y-6">
                        @include('admin.students.partials._base', ['student' => $student])
                    </div>
                    {{-- Right Sidebar: Additional Fields --}}
                    <div class="course-sidebar">
                        @include('admin.students.partials._additional', ['student' => $student])
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-primary-button class="px-8" type="submit">{{ __('Update') }}</x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
