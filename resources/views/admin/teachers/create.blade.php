<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Teacher') }}
        </h2>
    </x-slot>

    <div class="customers-page admin-page">
        {{-- Breadcrumbs --}}
        <x-admin.breadcrumbs page="Teachers / Create" />

        <div class="space-y-5 sm:space-y-6">

            <form action="{{ route('admin.teachers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="course-edit-layout">
                    {{-- Left Sidebar: Users and Translatable Content --}}
                    <div class="course-main-content space-y-6">
                        @include('admin.teachers.partials._translatable', ['locales' => App\Helpers\Helper::getLocales()])
                    </div>
                    {{-- Right Sidebar: Basic Fields --}}
                    <div class="course-sidebar">
                        @include('admin.teachers.partials._base')
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <x-primary-button class="px-8" type="submit">{{ __('Save') }}</x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
