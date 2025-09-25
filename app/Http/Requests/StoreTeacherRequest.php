<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'photo' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'email' => ['nullable','email'],
            'is_featured' => ['sometimes','boolean'],
            'social_ig' => ['nullable','string','max:255'],
            'social_youtube' => ['nullable','string','max:255'],

            'translations' => ['required','array','min:1'],
            'translations.*.locale' => ['required','string','max:5'],
            'translations.*.first_name' => ['required','string','max:255'],
            'translations.*.last_name' => ['required','string','max:255'],
            'translations.*.bio' => ['nullable','string'],
            'translations.*.specializations' => ['nullable'],
            'translations.*.position' => ['nullable','string','max:150'],
            'translations.*.church_name' => ['nullable','string','max:150'],
            'translations.*.city' => ['nullable','string','max:100'],
            'translations.*.country' => ['nullable','string','max:100'],
        ];
    }
}


