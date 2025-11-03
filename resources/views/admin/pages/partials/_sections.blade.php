{{-- Page Sections Management (Inline with Alpine.js) --}}
<div class="mb-10 rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-white/[0.03]"
     x-data="pageSectionsManager(@js(
        $page->sections->map(function($s) {
            return [
                'id'            => $s->id,
                'section_type'  => $s->section_type,
                'order'         => $s->order,
                'is_active'     => (bool) $s->is_active,
                'settings'      => $s->settings ?? [],
                'translations'  => $s->translations->map(fn($t) => [
                    'locale'    => $t->locale,
                    'title'     => $t->title,
                    'subtitle'  => $t->subtitle,
                    'content'   => $t->content,
                    'cta_text'  => $t->cta_text,
                    'cta_link'  => $t->cta_link,
                ])->toArray()
            ];
        })->values()->toArray()
     ))"
     x-cloak>

    {{-- Header --}}
    <div class="px-5 py-3 sm:px-6 sm:py-4 flex justify-between items-center border-top-radius bg-ghostwhite dark:bg-gray-dark">
        <h3 class="py-1 text-base font-medium text-gray-800 dark:text-white/90">Page Sections</h3>
        <button
            type="button"
            @click="addNewSection()"
            class="inline-flex items-center px-4 py-2 bg-blue-button hover:bg-blue-button text-white text-sm font-medium rounded-lg transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Section
        </button>
    </div>

    <div class="space-y-4 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-700">

        {{-- Display validation errors for sections --}}
        @if($errors->has('sections') || $errors->has('sections.*'))
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <p class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">Section Validation Errors:</p>
                <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1">
                    @foreach($errors->get('sections') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    @foreach($errors->get('sections.*') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    @foreach($errors->get('sections.*.section_type') as $error)
                        <li>Section Type: {{ $error }}</li>
                    @endforeach
                    @foreach($errors->get('sections.*.translations') as $error)
                        <li>Translations: {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- No sections message --}}
        <template x-if="sections.length === 0">
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="mt-2 text-sm font-medium text-gray-800 dark:text-white/90">No sections yet</p>
                <p class="mt-1 text-xs">Click "Add Section" button above to get started</p>
            </div>
        </template>

        {{-- Sections List --}}
        <div class="space-y-4">
            <template x-for="(section, index) in sections" :key="section.id ?? index">
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mb-2">
                    {{-- Section Header (collapsed view) --}}
                    <div class="p-4 bg-gray-50 dark:bg-gray-900 flex items-center justify-between cursor-pointer"
                         @click="section.expanded = !section.expanded">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-medium text-gray-900 dark:text-white"
                                  x-text="formatSectionType(section.section_type)"></span>
                            <template x-if="!section.is_active">
                                <span class="px-2 py-1 text-xs bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded">Inactive</span>
                            </template>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" @click.stop="deleteSection(index)"
                                    class="px-3 py-1.5 text-xs text-red-button hover:bg-red-50 dark:hover:bg-red-900/20 rounded">
                                Delete
                            </button>
                            <x-admin.svgs.down_arrow />
                        </div>
                    </div>

                    {{-- Section Form (expanded view) --}}
                    <div x-show="section.expanded"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="p-5 space-y-4 border-t border-gray-200 dark:border-gray-700">

                        {{-- Hidden field for section ID --}}
                        <input type="hidden" :name="inputName('id', index)" x-model="section.id">

                        {{-- Section Type & Order --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section Type <span class="text-red-500">*</span></label>
                                <select :name="inputName('section_type', index)" 
                                        x-model="section.section_type"
                                        required
                                        class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2.5 text-gray-900 dark:text-white text-sm">
                                    @foreach(\App\Models\PageSection::SECTION_TYPES as $type)
                                        <option value="{{ $type }}">
                                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                                            @if($type === 'hero') / Banner Section
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Order</label>
                                <input type="number" :name="inputName('order', index)" x-model="section.order"
                                       class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2.5 text-gray-900 dark:text-white">
                            </div>
                        </div>

                        {{-- Active Checkbox --}}
                        <div class="flex items-center">
                            <label :for="checkboxId(index)" class="mt-2 inline-flex items-center gap-2 cursor-pointer select-none">
                                <input type="hidden" :name="inputName('is_active', index)" value="0">
                                <input
                                    type="checkbox"
                                    :id="checkboxId(index)"
                                    :name="inputName('is_active', index)"
                                    value="1"
                                    class="sr-only"
                                    x-model="section.is_active"
                                >
                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-sm border-[1.25px]"
                                    :class="section.is_active ? 'bg-blue-button bg-blue-border' : 'border-gray-300 dark:border-gray-700'"
                                    aria-hidden="true"
                                >
                                    <span :class="section.is_active ? 'opacity-100' : 'opacity-0'" class="transition-opacity">
                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 3L4.5 8.5L2 6" stroke="white" stroke-width="1.6666" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                </span>
                                <span class="text-sm text-gray-700 dark:text-gray-400">Active (visible on page)</span>
                            </label>
                        </div>

                        {{-- Type-specific content (dynamic template loading from sections folder) --}}
                        <div class="pt-4">
                            @foreach(\App\Models\PageSection::SECTION_TYPES as $type)
                                <template x-if="section.section_type === '{{ $type }}'">
                                    <div>
                                        @include("admin.pages.sections.{$type}")
                                    </div>
                                </template>
                            @endforeach
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Hidden deleted sections field --}}
        <template x-for="(id, dIndex) in deletedSections" :key="dIndex">
            <input type="hidden" :name="`deleted_sections[${dIndex}]`" :value="id">
        </template>
    </div>
</div>

{{-- Inject PHP variables into JavaScript --}}
<script>
    window.pageSectionsConfig = {
        locales: @json(array_keys(App\Helpers\Helper::getLocales()))
    };
</script>
{{-- pages-sections.js is loaded via custom-admin.js --}}
