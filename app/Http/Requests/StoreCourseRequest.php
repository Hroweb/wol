<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'academic_year' => ['nullable','string','max:20'],
            'start_date' => ['required','date'],
            'end_date' => ['required','date','after_or_equal:start_date'],

            'translations' => ['required','array'],
            'translations.en' => ['nullable','array'],
            'translations.hy' => ['nullable','array'],
            'translations.en.locale' => ['nullable','in:en'],
            'translations.hy.locale' => ['nullable','in:hy'],

            // Only require fields if locale is present (allow either EN or HY)
            'translations.en.title' => ['required_with:translations.en','string','max:255'],
            'translations.en.slug' => ['nullable','string','max:255'],
            'translations.en.description' => ['required_with:translations.en','string'],
            'translations.en.curriculum_pdf_url' => ['nullable','url','max:255'],
            'translations.en.welcome_video_url' => ['nullable','url','max:255'],

            'translations.hy.title' => ['required_with:translations.hy','string','max:255'],
            'translations.hy.slug' => ['nullable','string','max:255'],
            'translations.hy.description' => ['required_with:translations.hy','string'],
            'translations.hy.curriculum_pdf_url' => ['nullable','url','max:255'],
            'translations.hy.welcome_video_url' => ['nullable','url','max:255'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $t = $this->input('translations', []);
            $en = $t['en'] ?? [];
            $hy = $t['hy'] ?? [];

            $enHas = !empty(trim($en['title'] ?? ''));
            $hyHas = !empty(trim($hy['title'] ?? ''));

            if (! $enHas && ! $hyHas) {
                $v->errors()->add('translations', 'At least one translation (English or Armenian) is required.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'translations.en.required_without' => 'Provide English or Armenian information.',
            'translations.hy.required_without' => 'Provide English or Armenian information.',

            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',
            'end_date.required' => 'End date is required.',
            'end_date.date' => 'End date must be a valid date.',

            'translations.en.title.required_with' => 'Title is required for English translation',
            'translations.en.slug.required_with'  => 'Slug is required for English translation',
            'translations.en.description.required_with' => 'Description is required for English translation',

            'translations.hy.title.required_with' => 'Title is required for Armenian translation',
            'translations.hy.slug.required_with'  => 'Slug is required for Armenian translation',
            'translations.hy.description.required_with' => 'Description is required for Armenian translation',
        ];
    }
}
