<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]" x-data="{ activeTab: '{{ array_key_first($locales) }}' }">
    <x-admin.locales :locales="$locales" :group="'Translatable Fields'" />
    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
        <div>
            @foreach($locales as $code => $label)
                <div x-show="activeTab === '{{ $code }}'" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="translations_{{ $code }}_first_name" value="First name" lang="{{ $code }}" />
                            <x-text-input id="translations_{{ $code }}_first_name" name="translations[{{ $code }}][first_name]" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('translations.'.$code.'.first_name', isset($teacher) ? optional($teacher->translations->firstWhere('locale', $code))->first_name : '') }}" />
                            <x-input-error :messages="$errors->get('translations.'.$code.'.first_name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="translations_{{ $code }}_last_name" value="Last name" lang="{{ $code }}" />
                            <x-text-input id="translations_{{ $code }}_last_name" name="translations[{{ $code }}][last_name]" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('translations.'.$code.'.last_name', isset($teacher) ? optional($teacher->translations->firstWhere('locale', $code))->last_name : '') }}" />
                            <x-input-error :messages="$errors->get('translations.'.$code.'.last_name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="translations_{{ $code }}_position" value="Position" lang="{{ $code }}" />
                            <x-text-input id="translations_{{ $code }}_position" name="translations[{{ $code }}][position]" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('translations.'.$code.'.position', isset($teacher) ? optional($teacher->translations->firstWhere('locale', $code))->position : '') }}" />
                            <x-input-error :messages="$errors->get('translations.'.$code.'.position')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="translations_{{ $code }}_church_name" value="Church name" lang="{{ $code }}" />
                            <x-text-input id="translations_{{ $code }}_church_name" name="translations[{{ $code }}][church_name]" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('translations.'.$code.'.church_name', isset($teacher) ? optional($teacher->translations->firstWhere('locale', $code))->church_name : '') }}" />
                            <x-input-error :messages="$errors->get('translations.'.$code.'.church_name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="translations_{{ $code }}_city" value="City" lang="{{ $code }}" />
                            <x-text-input id="translations_{{ $code }}_city" name="translations[{{ $code }}][city]" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('translations.'.$code.'.city', isset($teacher) ? optional($teacher->translations->firstWhere('locale', $code))->city : '') }}" />
                            <x-input-error :messages="$errors->get('translations.'.$code.'.city')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="translations_{{ $code }}_country" value="Country" lang="{{ $code }}" />
                            <x-text-input id="translations_{{ $code }}_country" name="translations[{{ $code }}][country]" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('translations.'.$code.'.country', isset($teacher) ? optional($teacher->translations->firstWhere('locale', $code))->country : '') }}" />
                            <x-input-error :messages="$errors->get('translations.'.$code.'.country')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="translations_{{ $code }}_bio" value="Bio" lang="{{ $code }}" />
                            <x-textarea id="translations_{{ $code }}_bio" name="translations[{{ $code }}][bio]" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('translations.'.$code.'.bio', isset($teacher) ? optional($teacher->translations->firstWhere('locale', $code))->bio : '') }}</x-textarea>
                            <x-input-error :messages="$errors->get('translations.'.$code.'.bio')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="translations_{{ $code }}_specializations" value="Specializations (comma separated)" lang="{{ $code }}" />
                            <x-text-input id="translations_{{ $code }}_specializations" name="translations[{{ $code }}][specializations]" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('translations.'.$code.'.specializations', isset($teacher) ? optional($teacher->translations->firstWhere('locale', $code))->specializations : '') }}" />
                            <x-input-error :messages="$errors->get('translations.'.$code.'.specializations')" class="mt-2" />
                        </div>
                        <input type="hidden" name="translations[{{ $code }}][locale]" value="{{ $code }}" />
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
