{{-- Reusable Section Content Form (Translations: title, subtitle, content, cta) --}}
{{-- Used by: hero, video, and other sections that need full translation fields --}}

@php
    $inputClass = 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30';
    $textareaClass = 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30';
@endphp

<input type="hidden" :name="`sections[${index}][translations][${tIndex}][locale]`" x-model="trans.locale">

<div class="space-y-4 mb-6 py-4 px-6 bg-ghostwhite dark:bg-gray-dark">
    {{-- Title --}}
    <div class="mb-3">
        <x-input-label x-bind:for="`sections_${index}_translations_${tIndex}_title`" x-bind:lang="trans.locale" value="Section Title" />
        <input type="text"
               :id="`sections_${index}_translations_${tIndex}_title`"
               :name="`sections[${index}][translations][${tIndex}][title]`"
               x-model="trans.title"
               class="{{ $inputClass }}">
    </div>

    {{-- Subtitle --}}
    <div class="mb-3">
        <x-input-label x-bind:for="`sections_${index}_translations_${tIndex}_subtitle`" x-bind:lang="trans.locale" value="Section Subtitle" />
        <input type="text"
               :id="`sections_${index}_translations_${tIndex}_subtitle`"
               :name="`sections[${index}][translations][${tIndex}][subtitle]`"
               x-model="trans.subtitle"
               class="{{ $inputClass }}">
    </div>

    {{-- Content --}}
    <div class="mb-3">
        <x-input-label x-bind:for="`sections_${index}_translations_${tIndex}_content`" x-bind:lang="trans.locale" value="Section Content" />
        <textarea
            :id="`sections_${index}_translations_${tIndex}_content`"
            :name="`sections[${index}][translations][${tIndex}][content]`"
            x-model="trans.content"
            rows="3"
            class="{{ $textareaClass }}"></textarea>
    </div>

    {{-- CTA Fields (optional - only if showCta is true) --}}
    @if(isset($showCta) && $showCta)
        <div class="flex gap-4">
            <div class="basis-0 flex-1 min-w-0">
                <x-input-label x-bind:for="`sections_${index}_translations_${tIndex}_cta_text`"
                               x-bind:lang="trans.locale" value="Button Text" />
                <input type="text"
                       :id="`sections_${index}_translations_${tIndex}_cta_text`"
                       :name="`sections[${index}][translations][${tIndex}][cta_text]`"
                       x-model="trans.cta_text"
                       class="{{ $inputClass }}">
            </div>

            <div class="basis-0 flex-1 min-w-0">
                <x-input-label x-bind:for="`sections_${index}_translations_${tIndex}_cta_link`"
                               x-bind:lang="trans.locale" value="Button Link" />
                <input type="text"
                       :id="`sections_${index}_translations_${tIndex}_cta_link`"
                       :name="`sections[${index}][translations][${tIndex}][cta_link]`"
                       x-model="trans.cta_link"
                       class="{{ $inputClass }}">
            </div>
        </div>


    @endif
</div>
