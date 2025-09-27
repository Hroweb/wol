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
            'email' => ['required','email'],
            'is_featured' => ['sometimes','boolean'],
            'social_ig' => ['nullable','string','max:255'],
            'social_youtube' => ['nullable','string','max:255'],

            'translations' => ['required','array'],
            'translations.en' => ['nullable','array'],
            'translations.hy' => ['nullable','array'],
            'translations.en.locale' => ['nullable','string','max:5','in:en'],
            'translations.hy.locale' => ['nullable','string','max:5','in:hy'],
            // EN fields are required unless HY equivalent is present
            'translations.en.first_name' => ['required_without:translations.hy.first_name','string','max:255'],
            'translations.en.last_name' => ['required_without:translations.hy.last_name','string','max:255'],
            'translations.en.bio' => ['required_without:translations.hy.bio','string'],
            'translations.en.specializations' => ['required_without:translations.hy.specializations'],
            'translations.en.position' => ['required_without:translations.hy.position','string','max:150'],
            'translations.en.church_name' => ['required_without:translations.hy.church_name','string','max:150'],
            'translations.en.city' => ['required_without:translations.hy.city','string','max:100'],
            'translations.en.country' => ['required_without:translations.hy.country','string','max:100'],
            // HY fields are required unless EN equivalent is present
            'translations.hy.first_name' => ['required_without:translations.en.first_name','string','max:255'],
            'translations.hy.last_name' => ['required_without:translations.en.last_name','string','max:255'],
            'translations.hy.bio' => ['required_without:translations.en.bio','string'],
            'translations.hy.specializations' => ['required_without:translations.en.specializations'],
            'translations.hy.position' => ['required_without:translations.en.position','string','max:150'],
            'translations.hy.church_name' => ['required_without:translations.en.church_name','string','max:150'],
            'translations.hy.city' => ['required_without:translations.en.city','string','max:100'],
            'translations.hy.country' => ['required_without:translations.en.country','string','max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'translations.en.first_name.required_without' => 'First Name is required',
            'translations.en.last_name.required_without' => 'Last Name is required',
            'translations.en.bio.required_without' => 'Bio is required',
            'translations.en.specializations.required_without' => 'Specializations are required',
            'translations.en.position.required_without' => 'Position is required',
            'translations.en.church_name.required_without' => 'Church Name is required',
            'translations.en.city.required_without' => 'City is required',
            'translations.en.country.required_without' => 'Country is required',

            'translations.hy.first_name.required_without' => 'First Name is required',
            'translations.hy.last_name.required_without' => 'Last Name is required',
            'translations.hy.bio.required_without' => 'Bio is required',
            'translations.hy.specializations.required_without' => 'Specializations are required',
            'translations.hy.position.required_without' => 'Position is required',
            'translations.hy.church_name.required_without' => 'Church Name is required',
            'translations.hy.city.required_without' => 'City is required',
            'translations.hy.country.required_without' => 'Country is required',
        ];
    }
}


