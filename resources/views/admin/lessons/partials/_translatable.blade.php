<div class="mb-10 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]" x-data="{ activeTab: '{{ array_key_first($locales) }}' }">
    <x-admin.locales :locales="$locales" :group="'Translatable Fields'" />
    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">

        <div>
            @foreach($locales as $code => $label)
                <div x-show="activeTab === '{{ $code }}'" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label for="translations_{{ $code }}_title" value="Title" lang="{{ $code }}" />
                            <x-text-input id="translations_{{ $code }}_title" name="translations[{{ $code }}][title]" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('translations.'.$code.'.title', isset($lesson) ? optional($lesson->translations->firstWhere('locale', $code))->title : '') }}" />
                            <x-input-error :messages="$errors->get('translations.'.$code.'.title')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="translations_{{ $code }}_description" value="Description" lang="{{ $code }}" />
                            <x-textarea id="translations_{{ $code }}_description" name="translations[{{ $code }}][description]" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('translations.'.$code.'.description', isset($lesson) ? optional($lesson->translations->firstWhere('locale', $code))->description : '') }}</x-textarea>
                            <x-input-error :messages="$errors->get('translations.'.$code.'.description')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="translations_{{ $code }}_materials" value="Materials (URLs, one per line)" lang="{{ $code }}" />
                            <x-textarea id="translations_{{ $code }}_materials" name="translations[{{ $code }}][materials]" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="https://example.com/material1.pdf&#10;https://example.com/material2.pdf">{{ old('translations.'.$code.'.materials', isset($lesson) ? optional($lesson->translations->firstWhere('locale', $code))->materials : '') }}</x-textarea>
                            <x-input-error :messages="$errors->get('translations.'.$code.'.materials')" class="mt-2" />
                        </div>
                        <input type="hidden" name="translations[{{ $code }}][locale]" value="{{ $code }}" />
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
