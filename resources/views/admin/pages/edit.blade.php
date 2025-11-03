<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Page') }}
        </h2>
    </x-slot>

    <div class="customers-page admin-page">
        {{-- Breadcrumbs --}}
        <x-admin.breadcrumbs page="Pages / Edit" />

        <div class="space-y-5 sm:space-y-6">

            <form action="{{ route('admin.pages.update', $page) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="course-edit-layout">
                    {{-- Left Sidebar: Page Title & Sections --}}
                    <div class="course-main-content space-y-6">
                        @include('admin.pages.partials._translatable', ['locales' => App\Helpers\Helper::getLocales(), 'page' => $page])

                        {{-- Page Sections (Inline Management) --}}
{{--                        @include('admin.pages.partials._sections', ['page' => $page])--}}
                    </div>
                    {{-- Right Sidebar: Basic Fields + Meta --}}
                    <div class="course-sidebar">
                        @include('admin.pages.partials._base', ['page' => $page])
                        @include('admin.pages.partials._meta', ['locales' => App\Helpers\Helper::getLocales(), 'page' => $page])
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-primary-button class="px-8" type="submit">{{ __('Update') }}</x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
