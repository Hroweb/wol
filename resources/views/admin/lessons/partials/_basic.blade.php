<div class="mb-10 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <x-admin.locales :group="'Basic Fields'" />
    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
        <div class="-mx-2.5 flex flex-wrap gap-y-5">
            <div class="w-full px-2.5">
                <x-input-label for="course_id" value="Course" />
                <select id="course_id" name="course_id" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    <option value="">Select a course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', isset($lesson) ? $lesson->course_id : '') == $course->id ? 'selected' : '' }}>
                            {{ $course->translations->firstWhere('locale', 'en')?->title ?? 'Course ' . $course->id }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
            </div>
            <div class="w-full px-2.5">
                <x-input-label for="lesson_date" value="Lesson Date" />
                <x-text-input id="lesson_date" name="lesson_date" type="date" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('lesson_date', isset($lesson) ? optional($lesson->lesson_date)->format('Y-m-d') : '') }}" />
                <x-input-error :messages="$errors->get('lesson_date')" class="mt-2" />
            </div>
        </div>
    </div>
</div>
