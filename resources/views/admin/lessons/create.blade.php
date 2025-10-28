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
                },

                playAudio(audioPath) {
                    if (!audioPath) return;

                    // Create audio element
                    const audioUrl = '{{ url('storage') }}/' + audioPath;

                    // Create a simple modal to show audio controls
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center';
                    modal.innerHTML = `
                        <div class="relative p-8 bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Audio Player</h3>
                                <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <audio controls class="w-full" autoplay>
                                <source src="${audioUrl}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    `;

                    // Close on outside click
                    modal.addEventListener('click', (e) => {
                        if (e.target === modal) {
                            modal.remove();
                        }
                    });

                    document.body.appendChild(modal);
                }
            }
        }
    </script>
</x-app-layout>
