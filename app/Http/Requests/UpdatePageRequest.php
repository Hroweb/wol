<?php

namespace App\Http\Requests;

use App\Models\PageSection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $page = $this->route('page');

        return [
            'slug' => ['required', 'string', 'max:255', Rule::unique('pages', 'slug')->ignore($page?->id)],
            'template' => ['nullable', 'string', 'max:255'],
            'is_published' => ['boolean'],
            'order' => ['integer'],

            'translations' => ['required', 'array'],
            'translations.en' => ['nullable', 'array'],
            'translations.hy' => ['nullable', 'array'],
            'translations.en.locale' => ['nullable', 'in:en'],
            'translations.hy.locale' => ['nullable', 'in:hy'],

            // Fields are nullable by default - we'll check requirements in withValidator
            'translations.en.title' => ['nullable', 'string', 'max:255'],
            'translations.en.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.en.meta_description' => ['nullable', 'string'],
            'translations.en.meta_keywords' => ['nullable', 'string', 'max:255'],
            'translations.en.content' => ['nullable', 'string'],

            'translations.hy.title' => ['nullable', 'string', 'max:255'],
            'translations.hy.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.hy.meta_description' => ['nullable', 'string'],
            'translations.hy.meta_keywords' => ['nullable', 'string', 'max:255'],
            'translations.hy.content' => ['nullable', 'string'],

            'sections' => ['nullable', 'array'],
            'sections.*.id' => ['nullable', 'integer', 'exists:page_sections,id'],
            'sections.*.section_type' => ['required', 'string', Rule::in(PageSection::SECTION_TYPES)],
            'sections.*.order' => ['nullable', 'integer'],
            'sections.*.is_active' => ['nullable', 'boolean'],
            'sections.*.settings' => ['nullable', 'array'],
            'sections.*.translations' => ['nullable', 'array'],
            'sections.*.translations.*.locale' => ['nullable', 'string', 'max:5'],
            'sections.*.translations.*.title' => ['nullable', 'string', 'max:255'],
            'sections.*.translations.*.subtitle' => ['nullable', 'string', 'max:255'],
            'sections.*.translations.*.content' => ['nullable', 'string'],
            'sections.*.translations.*.cta_text' => ['nullable', 'string', 'max:255'],
            'sections.*.translations.*.cta_link' => ['nullable', 'string', 'max:255'],

            'deleted_sections' => ['nullable', 'array'],
            'deleted_sections.*' => ['nullable', 'integer', 'exists:page_sections,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $t = $this->input('translations', []);
            $en = $t['en'] ?? [];
            $hy = $t['hy'] ?? [];

            // Helper function to check if a value is actually filled (not empty after trim and stripping HTML)
            $isFilled = function($value) {
                if (empty($value)) {
                    return false;
                }
                // Strip HTML tags and trim
                $cleaned = trim(strip_tags($value));
                // Remove common empty HTML patterns from rich text editors
                $cleaned = preg_replace('/^<p[^>]*>(\s|&nbsp;|<br\s*\/?>)*<\/p>$/i', '', $cleaned);
                $cleaned = preg_replace('/&nbsp;/', ' ', $cleaned);
                $cleaned = trim($cleaned);
                return !empty($cleaned);
            };

            // Check if user actually filled data for each locale
            // Exclude the locale field itself from the check
            $enHasData = $isFilled($en['title'] ?? '') ||
                $isFilled($en['content'] ?? '') ||
                $isFilled($en['meta_title'] ?? '') ||
                $isFilled($en['meta_description'] ?? '') ||
                $isFilled($en['meta_keywords'] ?? '');

            $hyHasData = $isFilled($hy['title'] ?? '') ||
                $isFilled($hy['content'] ?? '') ||
                $isFilled($hy['meta_title'] ?? '') ||
                $isFilled($hy['meta_description'] ?? '') ||
                $isFilled($hy['meta_keywords'] ?? '');

            // If English data is provided, title is required
            if ($enHasData && !$isFilled($en['title'] ?? '')) {
                $v->errors()->add('translations.en.title', 'Title is required when providing English translation data.');
            }

            // If Armenian data is provided, title is required
            if ($hyHasData && !$isFilled($hy['title'] ?? '')) {
                $v->errors()->add('translations.hy.title', 'Title is required when providing Armenian translation data.');
            }

            // At least one translation with a title must be provided
            $enHasTitle = $isFilled($en['title'] ?? '');
            $hyHasTitle = $isFilled($hy['title'] ?? '');

            if (!$enHasTitle && !$hyHasTitle) {
                $v->errors()->add('translations', 'At least one translation (English or Armenian) with a title is required.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'slug.required' => 'Slug is required.',
            'slug.unique' => 'This slug is already taken. Please choose a different one.',
            'slug.max' => 'Slug cannot exceed 255 characters.',

            'translations.required' => 'At least one translation is required.',
            'translations.en.title.required_with' => 'Title is required for English translation.',
            'translations.hy.title.required_with' => 'Title is required for Armenian translation.',
            'translations.en.title.max' => 'English title cannot exceed 255 characters.',
            'translations.hy.title.max' => 'Armenian title cannot exceed 255 characters.',
            'translations.en.meta_title.max' => 'English meta title cannot exceed 255 characters.',
            'translations.hy.meta_title.max' => 'Armenian meta title cannot exceed 255 characters.',
            'translations.en.meta_keywords.max' => 'English meta keywords cannot exceed 255 characters.',
            'translations.hy.meta_keywords.max' => 'Armenian meta keywords cannot exceed 255 characters.',

            'sections.*.section_type.in' => 'Invalid section type selected.',
            'sections.*.id.exists' => 'One or more selected sections do not exist.',
            'sections.*.translations.*.title.max' => 'Section title cannot exceed 255 characters.',
            'sections.*.translations.*.cta_text.max' => 'CTA text cannot exceed 255 characters.',
            'sections.*.translations.*.cta_link.max' => 'CTA link cannot exceed 255 characters.',
            'deleted_sections.*.exists' => 'One or more selected sections to delete do not exist.',
        ];
    }
}

