{{-- Hero Section: Settings + Translations --}}

{{-- Settings Section --}}
<div class="space-y-4">
    {{-- Translations Section --}}
    <div class="mb-3" x-data="{ activeTab: '{{ array_key_first(App\Helpers\Helper::getLocales()) }}' }">
        <x-admin.locales :locales="App\Helpers\Helper::getLocales()" :group="'Section Content'" />

        <div class="mt-4 space-y-4">
            <template x-for="(trans, tIndex) in section.translations" :key="tIndex">
                <div x-show="activeTab === trans.locale" x-cloak>
                    @include('admin.pages.sections.parts._section_content', ['showCta' => true])
                </div>
            </template>
        </div>
    </div>
    <x-admin.locales :locales="[]" :group="'Section Settings'" />
{{--    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Hero Settings</h4>--}}
    <div class="p-6 bg-ghostwhite dark:bg-gray-dark">
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Background Image URL</label>
            <input type="text" :name="`sections[${index}][settings][image]`" x-model="section.settings.image"
                   placeholder="/img/hero-bg.jpg or https://example.com/image.jpg"
                   class="text-sm w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2.5 text-gray-900 dark:text-white focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800">
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Layout</label>
            <select :name="`sections[${index}][settings][layout]`" x-model="section.settings.layout"
                    class="text-sm w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white px-4 py-2.5 text-gray-900 dark:text-white focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800">
                <option value="centered">Centered</option>
                <option value="left">Left Aligned</option>
                <option value="right">Right Aligned</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Minimum Height (px)</label>
            <input type="number" :name="`sections[${index}][settings][min_height]`" x-model="section.settings.min_height"
               placeholder="500"
               class="text-sm w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white px-4 py-2.5 text-gray-900 dark:text-white focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800">
        </div>
    </div>
</div>
