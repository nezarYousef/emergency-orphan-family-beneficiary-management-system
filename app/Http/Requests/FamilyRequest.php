<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FamilyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'data_entry'], true);
    }

    public function rules(): array
    {
        return [
            'head_of_household_name' => ['required', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:50', Rule::unique('families', 'national_id')->ignore($this->route('family'))],
            'phone' => ['nullable', 'string', 'max:40'],
            'governorate' => ['required', 'string', 'max:100'],
            'area' => ['required', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:1000'],
            'family_size' => ['required', 'integer', 'min:1', 'max:1000'],
            'provider_status' => ['required', 'in:has_provider,no_provider,deceased_provider,missing_provider,disabled_provider'],
            'vulnerability_status' => ['required', 'in:low,medium,high,critical'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
