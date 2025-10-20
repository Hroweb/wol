<div class="mb-10 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]" x-data="{ activeTab: '{{ array_key_first($locales) }}' }">
    <x-admin.locales :locales="$locales" :group="'Translatable Fields'" />
    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">

        <div>
            @foreach($locales as $code => $label)
                <div x-show="activeTab === '{{ $code }}'" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label for="translations_{{ $code }}_title" value="Title" lang="{{ $code }}" />
                            <x-text-input id="translations_{{ $code }}_title" name="translations[{{ $code }}][title]" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('translations.'.$code.'.title', $lessonTranslationsData[$code]['title'] ?? '') }}" />
                            <x-input-error :messages="$errors->get('translations.'.$code.'.title')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="translations_{{ $code }}_description" value="Description" lang="{{ $code }}" />
                            <x-textarea id="translations_{{ $code }}_description" name="translations[{{ $code }}][description]" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('translations.'.$code.'.description', $lessonTranslationsData[$code]['description'] ?? '') }}</x-textarea>
                            <x-input-error :messages="$errors->get('translations.'.$code.'.description')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="translations_{{ $code }}_materials" value="Materials (PDF Files)" lang="{{ $code }}" />

                            @if(isset($lessonTranslationsData[$code]['materials']) && is_array($lessonTranslationsData[$code]['materials']) && !empty($lessonTranslationsData[$code]['materials']))
                                <div class="mb-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Materials:</h4>
                                    @foreach($lessonTranslationsData[$code]['materials'] as $index => $file)
                                        <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                                            <div class="flex items-center">
                                                <x-admin.svgs.file-icon />
                                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $file['name'] }}</span>
                                                <span class="text-xs text-gray-500 ml-2">({{ number_format($file['size'] / 1024, 1) }} KB)</span>
                                            </div>
                                            <div class="flex items-center space-x-2 justify-between w-20">
                                                <a href="{{ Storage::url($file['path']) }}" target="_blank" class="flex items-center text-blue-button hover:text-blue-button text-xs">
{{--                                                    <x-admin.svgs.eye-icon />--}}
                                                    View
                                                </a>
                                                <button
                                                    type="button"
                                                    onclick="deleteMaterial('{{ $code }}', {{ $index }})"
                                                    class="flex items-center text-red-button hover:text-red-button text-xs font-medium"
                                                >
{{--                                                    <x-admin.svgs.trash-icon />--}}
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <input
                                type="file"
                                id="translations_{{ $code }}_materials"
                                name="translations[{{ $code }}][materials][]"
                                accept=".pdf,application/pdf"
                                multiple
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            />
                            <div class="mt-1 text-sm text-gray-500">Upload multiple PDF files (hold Ctrl/Cmd to select multiple)</div>
                            <x-input-error :messages="$errors->get('translations.'.$code.'.materials')" class="mt-2" />
                        </div>

                        <input type="hidden" name="translations[{{ $code }}][locale]" value="{{ $code }}" />
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
