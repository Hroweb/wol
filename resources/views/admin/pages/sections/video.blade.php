{{-- Video Section: Settings + Translations --}}

{{-- Settings Section --}}
<div class="space-y-4">
    {{-- Translations Section --}}
    <div class="mb-3" x-data="{ activeTab: '{{ array_key_first(App\Helpers\Helper::getLocales()) }}' }">
        <x-admin.locales :locales="App\Helpers\Helper::getLocales()" :group="'Section Content'" />

        <div class="mt-4 space-y-4">
            <template x-for="(trans, tIndex) in section.translations" :key="tIndex">
                <div x-show="activeTab === trans.locale" x-cloak>
                    @include('admin.pages.sections.parts._section_content', ['showCta' => false])
                </div>
            </template>
        </div>
    </div>

    <x-admin.locales :locales="[]" :group="'Section Settings'" />

    <div class="p-6 bg-ghostwhite dark:bg-gray-dark">
        <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Video URL</label>
        <input type="url" :name="`sections[${index}][settings][video_url]`" x-model="section.settings.video_url"
               placeholder="https://www.youtube.com/embed/VIDEO_ID"
               class="text-sm w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-2.5 text-gray-900 dark:text-white">
        <p class="mt-1 text-xs text-gray-500">Use YouTube embed URL format</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Aspect Ratio</label>
            <select :name="`sections[${index}][settings][aspect_ratio]`" x-model="section.settings.aspect_ratio"
                    class="text-sm w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-2.5 text-gray-900 dark:text-white">
                <option value="16:9">16:9 (Widescreen)</option>
                <option value="4:3">4:3 (Standard)</option>
                <option value="1:1">1:1 (Square)</option>
            </select>
        </div>
{{--        autoplay checkbox--}}
        <div class="flex items-center mt-3">
            <x-admin.checkbox-alpine
                name="`sections[${index}][settings][autoplay]`"
                model="section.settings.autoplay"
                text="Autoplay video" />
        </div>
    </div>
</div>
