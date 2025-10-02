<div class="mb-10 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <x-admin.locales :group="'Basic Fields'" />
    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
        <div class="-mx-2.5 flex flex-wrap gap-y-5">
            <div class="w-full px-2.5 xl:w-1/3">
                <x-input-label for="academic_year" value="Academic Year" />
                <x-text-input id="academic_year" name="academic_year" type="text" placeholder="2025-2026" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('academic_year', isset($course) ? $course->academic_year : '') }}" />
                <x-input-error :messages="$errors->get('academic_year')" class="mt-2" />
            </div>
            <div class="w-full px-2.5 xl:w-1/3">
                <x-input-label for="start_date" value="Start Date" />
                <x-text-input id="start_date" name="start_date" type="date" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('start_date', isset($course) ? optional($course->start_date)->format('Y-m-d') : '') }}" />
                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
            </div>
            <div class="w-full px-2.5 xl:w-1/3">
                <x-input-label for="end_date" value="End Date" />
                <x-text-input id="end_date" name="end_date" type="date" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('end_date', isset($course) ? optional($course->end_date)->format('Y-m-d') : '') }}" />
                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
            </div>
        </div>
    </div>
</div>
