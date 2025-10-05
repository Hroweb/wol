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
                        @include('admin.lessons.partials._lesson_parts', ['lesson' => $lesson])
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

    <script>
        const teachersData = @json($teachersData);
        const lessonPartsData = @json($lessonPartsData);

        // Debug: Log the data to console
        console.log('Teachers Data:', teachersData);
        console.log('Lesson Parts Data:', lessonPartsData);

        function lessonForm() {
            const parts = lessonPartsData.length > 0 ? lessonPartsData : [
                {
                    id: 1,
                    teacher_id: '',
                    part_number: 1,
                    audio_file_urls: '',
                    duration_minutes: ''
                }
            ];

            console.log('Initialized lesson parts:', parts);

            return {
                lessonParts: parts,

                init() {
                    // Force reactivity update after initialization
                    this.$nextTick(() => {
                        console.log('Alpine.js initialized, lesson parts:', this.lessonParts);

                        // Manually set selected teachers after DOM is ready
                        this.lessonParts.forEach((part, index) => {
                            if (part.teacher_id) {
                                const selectElement = document.getElementById(`teacher_id_${part.id}`);
                                if (selectElement) {
                                    selectElement.value = part.teacher_id;
                                    console.log(`Set teacher ${part.teacher_id} for part ${index + 1}`);
                                }
                            }
                        });
                    });
                },

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
