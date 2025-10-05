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
                        @include('admin.lessons.partials._lesson_parts')
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

    <script>
        const teachersData = @json($teachersData);

        function lessonForm() {
            return {
                lessonParts: [
                    {
                        id: 1,
                        teacher_id: '',
                        part_number: 1,
                        audio_file_urls: '',
                        duration_minutes: ''
                    }
                ],

                addLessonPart() {
                    if (this.lessonParts.length < 2) {
                        const nextPartNumber = this.lessonParts.length + 1;
                        this.lessonParts.push({
                            id: Date.now(),
                            teacher_id: '',
                            part_number: nextPartNumber,
                            audio_file_urls: '',
                            duration_minutes: ''
                        });
                    }
                },

                removeLessonPart(index) {
                    if (this.lessonParts.length > 1) {
                        this.lessonParts.splice(index, 1);
                        // Reassign part numbers
                        this.lessonParts.forEach((part, idx) => {
                            part.part_number = idx + 1;
                        });
                    }
                },

                getTeachers() {
                    return teachersData;
                }
            }
        }
    </script>
</x-app-layout>
