<div class="mb-10 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]" x-data="{ activeTab: '{{ array_key_first($locales) }}' }">
    <x-admin.locales :locales="$locales" />
    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">

        <div class="pt-4">
            @foreach($locales as $code => $label)
                <div x-show="activeTab === '{{ $code }}'" x-cloak>
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="translations_{{ $code }}_title" value="Title ({{ strtoupper($code) }})" />
                            <x-text-input id="translations_{{ $code }}_title" name="translations[{{ $code }}][title]" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('translations.'.$code.'.title', isset($course) ? optional($course->translations->firstWhere('locale', $code))->title : '') }}" />
                            <x-input-error :messages="$errors->get('translations.'.$code.'.title')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="translations_{{ $code }}_slug" value="Slug ({{ strtoupper($code) }})" />
                            <x-text-input id="translations_{{ $code }}_slug" name="translations[{{ $code }}][slug]" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('translations.'.$code.'.slug', isset($course) ? optional($course->translations->firstWhere('locale', $code))->slug : '') }}" />
                            <x-input-error :messages="$errors->get('translations.'.$code.'.slug')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="translations_{{ $code }}_description" value="Description ({{ strtoupper($code) }})" />
                            <x-textarea id="translations_{{ $code }}_description" name="translations[{{ $code }}][description]" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('translations.'.$code.'.description', isset($course) ? optional($course->translations->firstWhere('locale', $code))->description : '') }}</x-textarea>
                            <x-input-error :messages="$errors->get('translations.'.$code.'.description')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="translations_{{ $code }}_curriculum_pdf_url" value="Curriculum PDF URL ({{ strtoupper($code) }})" />
                            <x-text-input id="translations_{{ $code }}_curriculum_pdf_url" name="translations[{{ $code }}][curriculum_pdf_url]" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('translations.'.$code.'.curriculum_pdf_url', isset($course) ? optional($course->translations->firstWhere('locale', $code))->curriculum_pdf_url : '') }}" />
                            <x-input-error :messages="$errors->get('translations.'.$code.'.curriculum_pdf_url')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="translations_{{ $code }}_welcome_video_url" value="Welcome Video URL ({{ strtoupper($code) }})" />
                            <x-text-input id="translations_{{ $code }}_welcome_video_url" name="translations[{{ $code }}][welcome_video_url]" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('translations.'.$code.'.welcome_video_url', isset($course) ? optional($course->translations->firstWhere('locale', $code))->welcome_video_url : '') }}" />
                            <x-input-error :messages="$errors->get('translations.'.$code.'.welcome_video_url')" class="mt-2" />
                        </div>
                        <input type="hidden" name="translations[{{ $code }}][locale]" value="{{ $code }}" />
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
