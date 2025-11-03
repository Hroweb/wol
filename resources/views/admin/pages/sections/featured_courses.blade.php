{{-- Featured Courses Section: Settings + Translations --}}

{{-- Settings Section --}}
<div class="space-y-4">

    {{-- Translations Section --}}
    <div class="mb-3" x-data="{ activeTab: '{{ array_key_first(App\Helpers\Helper::getLocales()) }}' }">
        <x-admin.locales :locales="App\Helpers\Helper::getLocales()" :group="'Section Heading'" />

        <div class="mt-4 space-y-4">
            <template x-for="(trans, tIndex) in section.translations" :key="tIndex">
                <div x-show="activeTab === trans.locale" x-cloak>
                    @include('admin.pages.sections.parts._section_heading')
                </div>
            </template>
        </div>
    </div>

    <x-admin.locales :locales="[]" :group="'Section Settings'" />

    <div class="p-6 bg-ghostwhite dark:bg-gray-dark">
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Courses</label>
            <select :name="`sections[${index}][settings][course_ids][]`" multiple
                    x-model="section.settings.course_ids"
                    class="text-sm w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-2.5 text-gray-900 dark:text-white"
                    size="6">
                @foreach($courses ?? [] as $course)
                    <option value="{{ $course['id'] }}">{{ $course['title'] }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple courses</p>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Display Limit</label>
            <input type="number" :name="`sections[${index}][settings][limit]`" x-model="section.settings.limit"
                   placeholder="6" min="1"
                   class="text-sm w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-2.5 text-gray-900 dark:text-white">
            <p class="mt-1 text-xs text-gray-500">Maximum number of courses to display</p>
        </div>

        {{--<div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Columns</label>
            <select :name="`sections[${index}][settings][columns]`" x-model="section.settings.columns"
                    class="text-sm w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-2.5 text-gray-900 dark:text-white">
                <option value="2">2 Columns</option>
                <option value="3">3 Columns</option>
                <option value="4">4 Columns</option>
            </select>
        </div>--}}
    </div>
</div>
