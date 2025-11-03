<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Lesson') }}
        </h2>
    </x-slot>

    <div class="customers-page admin-page">
        {{-- Breadcrumbs --}}
        <x-admin.breadcrumbs page="Lessons / Create" />

        <div class="space-y-5 sm:space-y-6">
            <form action="{{ route('admin.lessons.store') }}" method="POST" enctype="multipart/form-data" x-data="lessonForm()">
                @csrf
                <div class="course-edit-layout">
                    {{-- Left Sidebar: Translatable Content --}}
                    <div class="course-main-content space-y-6">
                        @include('admin.lessons.partials._translatable', ['locales' => App\Helpers\Helper::getLocales()])
                        @include('admin.lessons.partials._lesson_parts', ['locales' => App\Helpers\Helper::getLocales()])
                    </div>
                    {{-- Right Sidebar: Basic Fields and Lesson Parts --}}
                    <div class="course-sidebar">
                        @include('admin.lessons.partials._basic')

                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-primary-button class="px-8" type="submit">{{ __('Save') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    {{-- Inject PHP variables into JavaScript --}}
    <script>
        window.lessonConfig = {
            teachersData: @json($teachersData),
            lessonPartsData: null, // Not needed for create mode
            locales: @json(App\Helpers\Helper::getLocales()),
            lessonId: null, // Not needed for create mode
            storageUrl: '{{ url('storage') }}',
            csrfToken: '{{ csrf_token() }}',
            deleteMaterialUrl: null, // Not needed for create mode
            deleteAudioUrl: null // Not needed for create mode
        };
    </script>
</x-app-layout>
