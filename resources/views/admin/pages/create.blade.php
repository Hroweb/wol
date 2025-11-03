<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Page') }}
        </h2>
    </x-slot>

    <div class="customers-page admin-page">
        {{-- Breadcrumbs --}}
        <x-admin.breadcrumbs page="Pages / Create" />

        <div class="space-y-5 sm:space-y-6">

            <form action="{{ route('admin.pages.store') }}" method="POST" x-data="{ slugManuallyEdited: false }" @generate-slug.window="if (!slugManuallyEdited) { const slug = window.generateSlugFromTitle($event.detail); if (slug) document.getElementById('slug').value = slug; }" @slug-manually-edited.window="slugManuallyEdited = true">
                @csrf
                <div class="course-edit-layout">
                    {{-- Left Sidebar: Page Title --}}
                    <div class="course-main-content space-y-6">
                        @include('admin.pages.partials._translatable', ['locales' => App\Helpers\Helper::getLocales()])

                        {{-- Page Sections (Inline Management) --}}
                        @php
                            $emptyPage = new \App\Models\Page();
                            $emptyPage->sections = collect([]);
                        @endphp
                        @include('admin.pages.partials._sections', ['page' => $emptyPage])
                    </div>
                    {{-- Right Sidebar: Basic Fields + Meta --}}
                    <div class="course-sidebar">
                        @include('admin.pages.partials._base')
                        @include('admin.pages.partials._meta', ['locales' => App\Helpers\Helper::getLocales()])
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-primary-button class="px-8" type="submit">{{ __('Save') }}</x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
