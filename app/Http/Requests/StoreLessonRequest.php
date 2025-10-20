<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            // Lesson basic fields
            'course_id' => ['required', 'exists:courses,id'],
            'lesson_date' => ['required', 'date'],

            // Lesson translations
            'translations' => ['required', 'array'],
            'translations.en' => ['nullable', 'array'],
            'translations.hy' => ['nullable', 'array'],
            'translations.en.locale' => ['nullable', 'in:en'],
            'translations.hy.locale' => ['nullable', 'in:hy'],

            // Lesson translation fields - only require if the translation has content
            'translations.en.title' => ['nullable', 'string', 'max:255'],
            'translations.en.description' => ['nullable', 'string'],
//            'translations.en.materials' => ['nullable', 'string'],

            'translations.hy.title' => ['nullable', 'string', 'max:255'],
            'translations.hy.description' => ['nullable', 'string'],
//            'translations.hy.materials' => ['nullable', 'string'],

            // Lesson parts (dynamic array)
            'lesson_parts' => ['required', 'array', 'min:1', 'max:2'],
            'lesson_parts.*.teacher_id' => ['required', 'exists:teachers,id'],
            'lesson_parts.*.part_number' => ['required', 'integer', 'min:1', 'max:2'],
            'lesson_parts.*.audio_file_urls' => ['nullable', 'string'],
            'lesson_parts.*.duration_minutes' => ['nullable', 'integer', 'min:1'],

            // File upload validations - dynamic based on available locales
            'translations.*.materials' => ['nullable', 'array'],
            'translations.*.materials.*' => ['file', 'mimes:pdf', 'max:10240'], // 10MB max
            'lesson_parts.*.translations.*.audio_file' => ['nullable', 'file', 'mimes:mp3', 'max:51200'], // 50MB max
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $t = $this->input('translations', []);
            $en = $t['en'] ?? [];
            $hy = $t['hy'] ?? [];

            // Check if at least one translation has a title
            $enHasTitle = !empty(trim($en['title'] ?? ''));
            $hyHasTitle = !empty(trim($hy['title'] ?? ''));

            if (!$enHasTitle && !$hyHasTitle) {
                $v->errors()->add('translations', 'At least one translation (English or Armenian) with a title is required.');
            }

            // Validate unique part numbers
            $parts = $this->input('lesson_parts', []);
            $partNumbers = collect($parts)->pluck('part_number')->toArray();
            $uniquePartNumbers = array_unique($partNumbers);

            if (count($partNumbers) !== count($uniquePartNumbers)) {
                $v->errors()->add('lesson_parts', 'Each lesson part must have a unique part number.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'Course is required.',
            'course_id.exists' => 'Selected course does not exist.',
            'lesson_date.required' => 'Lesson date is required.',
            'lesson_date.date' => 'Lesson date must be a valid date.',

            'translations.required' => 'Translations are required.',
            'translations.en.title.required_with' => 'Title is required for English translation.',
            'translations.hy.title.required_with' => 'Title is required for Armenian translation.',

            'lesson_parts.required' => 'At least one lesson part is required.',
            'lesson_parts.min' => 'At least one lesson part is required.',
            'lesson_parts.max' => 'Maximum 2 lesson parts allowed.',
            'lesson_parts.*.teacher_id.required' => 'Teacher is required for each lesson part.',
            'lesson_parts.*.teacher_id.exists' => 'Selected teacher does not exist.',
            'lesson_parts.*.part_number.required' => 'Part number is required.',
            'lesson_parts.*.part_number.integer' => 'Part number must be a number.',
            'lesson_parts.*.part_number.min' => 'Part number must be at least 1.',
            'lesson_parts.*.part_number.max' => 'Part number cannot exceed 2.',
            'lesson_parts.*.duration_minutes.integer' => 'Duration must be a number.',
            'lesson_parts.*.duration_minutes.min' => 'Duration must be at least 1 minute.',
        ];
    }
}
