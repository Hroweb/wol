<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Lesson') }}
        </h2>
    </x-slot>

    <div class="customers-page admin-page">
        {{-- Breadcrumbs --}}
        <x-admin.breadcrumbs page="Lessons / Edit" />

        <div class="space-y-5 sm:space-y-6">
            <form action="{{ route('admin.lessons.update', $lesson) }}" method="POST" enctype="multipart/form-data" x-data="lessonForm()" x-init="init()">
                @csrf
                @method('PUT')
                <div class="course-edit-layout">
                    {{-- Left Sidebar: Translatable Content --}}
                    <div class="course-main-content space-y-6">
                        @include('admin.lessons.partials._translatable', ['locales' => App\Helpers\Helper::getLocales(), 'lesson' => $lesson])
                        @include('admin.lessons.partials._lesson_parts', ['locales' => App\Helpers\Helper::getLocales(), 'lesson' => $lesson])
                    </div>
                    {{-- Right Sidebar: Basic Fields and Lesson Parts --}}
                    <div class="course-sidebar">
                        @include('admin.lessons.partials._basic', ['lesson' => $lesson])

                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-primary-button class="px-8" type="submit">{{ __('Update') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    {{-- Inject PHP variables into JavaScript --}}
    <script>
        window.lessonConfig = {
            teachersData: @json($teachersData),
            lessonPartsData: @json($lessonPartsData),
            locales: @json(App\Helpers\Helper::getLocales()),
            lessonId: {{ $lesson->id }},
            storageUrl: '{{ url('storage') }}',
            csrfToken: '{{ csrf_token() }}',
            deleteAudioUrl: '{{ route('admin.lessons.audio.delete', $lesson->id) }}',
            deleteMaterialBaseUrl: '{{ route('admin.lessons.materials.delete', ['lesson' => $lesson->id, 'locale' => ':locale', 'index' => ':index']) }}'
        };

        // Debug: Log the data to console - remove after testing
        console.log('Teachers Data:', window.lessonConfig.teachersData);
        console.log('Lesson Parts Data:', window.lessonConfig.lessonPartsData);
    </script>
</x-app-layout>
