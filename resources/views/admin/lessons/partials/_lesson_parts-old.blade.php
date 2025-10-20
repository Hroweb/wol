<div class="mb-10 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <x-admin.locales :locales="$locales" :group="'Lesson Parts'" />
    {{--<div class="flex items-center justify-between p-5 sm:p-6 border-b border-gray-100 dark:border-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Lesson Parts</h3>
        <button type="button" @click="addLessonPart()" x-show="lessonParts.length < 2" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-button hover:bg-blue-button focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Part
        </button>
    </div>--}}

    <div class="p-5 sm:p-6 space-y-6">
        <template x-for="(part, index) in lessonParts" :key="part.id || 'new-' + index">
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-md font-medium text-gray-900 dark:text-white">Part <span x-text="part.part_number"></span></h4>
                    <button type="button" @click="removeLessonPart(index)" x-show="lessonParts.length > 1" class="text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label x-bind:for="'teacher_id_' + part.id" value="Teacher" />
                        <select x-bind:id="'teacher_id_' + part.id" x-bind:name="'lesson_parts[' + index + '][teacher_id]'" x-model="part.teacher_id" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            <option value="">Select a teacher</option>
                            <template x-for="teacher in getTeachers()" :key="teacher.id">
                                <option :value="teacher.id" x-text="teacher.name"></option>
                            </template>
                        </select>
                        <div class="mt-2 text-sm text-red-600" x-show="false" x-text="''"></div>
                    </div>

                    <div>
                        <x-input-label x-bind:for="'duration_minutes_' + part.id" value="Duration (minutes)" />
                        <x-text-input x-bind:id="'duration_minutes_' + part.id" x-bind:name="'lesson_parts[' + index + '][duration_minutes]'" type="number" x-model="part.duration_minutes" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="60" />
                        <div class="mt-2 text-sm text-red-600" x-show="false" x-text="''"></div>
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label x-bind:for="'audio_file_urls_' + part.id" value="Audio File URLs (one per line)" />
                        <x-textarea x-bind:id="'audio_file_urls_' + part.id" x-bind:name="'lesson_parts[' + index + '][audio_file_urls]'" x-model="part.audio_file_urls" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="https://example.com/audio1.mp3&#10;https://example.com/audio2.mp3" rows="3"></x-textarea>
                        <div class="mt-2 text-sm text-red-600" x-show="false" x-text="''"></div>
                    </div>
                </div>

                <!-- Hidden field for part number -->
                <input type="hidden" :name="'lesson_parts[' + index + '][part_number]'" x-model="part.part_number" />
            </div>
        </template>

        <div x-show="lessonParts.length === 0" class="text-center py-8 text-gray-500">
            No lesson parts added yet. Click "Add Part" to get started.
        </div>
    </div>
</div>
