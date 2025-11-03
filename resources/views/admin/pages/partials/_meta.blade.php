<div class="mb-10 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]" x-data="{ activeTab: '{{ array_key_first($locales) }}' }">
    <x-admin.locales :locales="$locales" :group="'SEO Meta Tags'" />
    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
        <div>
            @foreach($locales as $code => $label)
                <div x-show="activeTab === '{{ $code }}'" x-cloak>
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="translations_{{ $code }}_meta_title" value="Meta Title" lang="{{ $code }}" />
                            <x-text-input id="translations_{{ $code }}_meta_title" name="translations[{{ $code }}][meta_title]" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('translations.'.$code.'.meta_title', isset($page) ? optional($page->translations->firstWhere('locale', $code))->meta_title : '') }}" />
                            <x-input-error :messages="$errors->get('translations.'.$code.'.meta_title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="translations_{{ $code }}_meta_description" value="Meta Description" lang="{{ $code }}" />
                            <x-textarea id="translations_{{ $code }}_meta_description" name="translations[{{ $code }}][meta_description]" rows="3" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('translations.'.$code.'.meta_description', isset($page) ? optional($page->translations->firstWhere('locale', $code))->meta_description : '') }}</x-textarea>
                            <x-input-error :messages="$errors->get('translations.'.$code.'.meta_description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="translations_{{ $code }}_meta_keywords" value="Meta Keywords" lang="{{ $code }}" />
                            <x-text-input id="translations_{{ $code }}_meta_keywords" name="translations[{{ $code }}][meta_keywords]" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('translations.'.$code.'.meta_keywords', isset($page) ? optional($page->translations->firstWhere('locale', $code))->meta_keywords : '') }}" />
                            <x-input-error :messages="$errors->get('translations.'.$code.'.meta_keywords')" class="mt-2" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

