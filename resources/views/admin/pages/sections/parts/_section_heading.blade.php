{{-- Simple Section Heading Form (Translations: title, subtitle only) --}}
{{-- Used by: featured_teachers, featured_courses, and other sections with minimal translation needs --}}

@php
    $inputClass = 'text-sm dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30';
@endphp

<input type="hidden" :name="`sections[${index}][translations][${tIndex}][locale]`" x-model="trans.locale">

<div class="space-y-4 px-6 py-4 bg-ghostwhite dark:bg-gray-dark">
    {{-- Title --}}
    <div class="mb-3">
        <x-input-label x-bind:for="`sections_${index}_translations_${tIndex}_title`" x-bind:lang="trans.locale" value="Section Title" />
        <input type="text"
               :id="`sections_${index}_translations_${tIndex}_title`"
               :name="`sections[${index}][translations][${tIndex}][title]`"
               x-model="trans.title"
               placeholder="e.g. Our Expert Teachers"
               class="{{ $inputClass }}">
    </div>

    {{-- Subtitle --}}
    <div>
        <x-input-label x-bind:for="`sections_${index}_translations_${tIndex}_subtitle`" x-bind:lang="trans.locale" value="Section Subtitle" />
        <input type="text"
           :id="`sections_${index}_translations_${tIndex}_subtitle`"
           :name="`sections[${index}][translations][${tIndex}][subtitle]`"
           x-model="trans.subtitle"
           placeholder="Optional description"
           class="{{ $inputClass }}">
    </div>
</div>

