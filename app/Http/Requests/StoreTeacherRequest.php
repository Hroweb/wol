<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * Normalize input so empty locale blocks don't trigger required_with.
     * - Trim strings
     * - If a locale block has no meaningful values -> remove it entirely
     */
    protected function prepareForValidation(): void
    {
        $t = $this->input('translations', []);

        foreach (['en', 'hy'] as $loc) {
            if (!isset($t[$loc]) || !is_array($t[$loc])) {
                continue;
            }

            // Trim strings
            foreach ($t[$loc] as $k => $v) {
                if (is_string($v)) {
                    $t[$loc][$k] = trim($v);
                }
            }

            // Normalize specializations if it's an array (drop empties)
            if (isset($t[$loc]['specializations']) && is_array($t[$loc]['specializations'])) {
                $t[$loc]['specializations'] = array_values(
                    array_filter($t[$loc]['specializations'], fn ($x) => !is_null($x) && $x !== '')
                );
            }

            // Decide if this locale has any meaningful content
            $keys = ['first_name','last_name','bio','position','church_name','city','country','specializations'];
            $hasMeaning = false;
            foreach ($keys as $k) {
                $val = $t[$loc][$k] ?? null;
                if (is_array($val)) {
                    if (count($val) > 0) { $hasMeaning = true; break; }
                } else {
                    if ($val !== null && $val !== '') { $hasMeaning = true; break; }
                }
            }

            if (!$hasMeaning) {
                unset($t[$loc]); // remove empty block so required_with won't trigger
            }
        }

        $this->merge(['translations' => $t]);
    }

    public function rules(): array
    {
        return [
            'photo'          => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'email'          => ['required','email'],
            'is_featured'    => ['sometimes','boolean'],
            'social_ig'      => ['nullable','string','max:255'],
            'social_youtube' => ['nullable','string','max:255'],

            'translations'   => ['required','array'],

            // At least one locale must be present (after prepareForValidation)
            'translations.en' => ['nullable','array','required_without:translations.hy'],
            'translations.hy' => ['nullable','array','required_without:translations.en'],

            // Locale codes are optional if you don't send them
            'translations.en.locale' => ['sometimes','string','max:5','in:en'],
            'translations.hy.locale' => ['sometimes','string','max:5','in:hy'],

            // If EN block exists, require these EN fields
            'translations.en.first_name'      => ['required_with:translations.en','string','max:255'],
            'translations.en.last_name'       => ['required_with:translations.en','string','max:255'],
            'translations.en.bio'             => ['required_with:translations.en','string'],
            'translations.en.specializations' => ['required_with:translations.en'],
            'translations.en.position'        => ['required_with:translations.en','string','max:150'],
            'translations.en.church_name'     => ['required_with:translations.en','string','max:150'],
            'translations.en.city'            => ['required_with:translations.en','string','max:100'],
            'translations.en.country'         => ['required_with:translations.en','string','max:100'],

            // If HY block exists, require these HY fields
            'translations.hy.first_name'      => ['required_with:translations.hy','string','max:255'],
            'translations.hy.last_name'       => ['required_with:translations.hy','string','max:255'],
            'translations.hy.bio'             => ['required_with:translations.hy','string'],
            'translations.hy.specializations' => ['required_with:translations.hy'],
            'translations.hy.position'        => ['required_with:translations.hy','string','max:150'],
            'translations.hy.church_name'     => ['required_with:translations.hy','string','max:150'],
            'translations.hy.city'            => ['required_with:translations.hy','string','max:100'],
            'translations.hy.country'         => ['required_with:translations.hy','string','max:100'],
        ];
    }

    public function withValidator($validator): void
    {
        // Optional: assert that at least one locale truly exists after normalization
        $validator->after(function ($v) {
            $t = $this->input('translations', []);
            if (empty($t['en']) && empty($t['hy'])) {
                $v->errors()->add('translations', 'Provide English or Armenian information.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'translations.en.required_without' => 'Provide English or Armenian information.',
            'translations.hy.required_without' => 'Provide English or Armenian information.',

            'translations.en.first_name.required_with' => 'First Name is required for English translation',
            'translations.en.last_name.required_with'  => 'Last Name is required for English translation',
            'translations.en.bio.required_with'        => 'Bio is required for English translation',
            'translations.en.specializations.required_with' => 'Specializations are required for English translation',
            'translations.en.position.required_with'   => 'Position is required for English translation',
            'translations.en.church_name.required_with'=> 'Church Name is required for English translation',
            'translations.en.city.required_with'       => 'City is required for English translation',
            'translations.en.country.required_with'    => 'Country is required for English translation',

            'translations.hy.first_name.required_with' => 'First Name is required for Armenian translation',
            'translations.hy.last_name.required_with'  => 'Last Name is required for Armenian translation',
            'translations.hy.bio.required_with'        => 'Bio is required for Armenian translation',
            'translations.hy.specializations.required_with' => 'Specializations are required for Armenian translation',
            'translations.hy.position.required_with'   => 'Position is required for Armenian translation',
            'translations.hy.church_name.required_with'=> 'Church Name is required for Armenian translation',
            'translations.hy.city.required_with'       => 'City is required for Armenian translation',
            'translations.hy.country.required_with'    => 'Country is required for Armenian translation',
        ];
    }
}
