<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BeneficiaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'data_entry'], true);
    }

    public function rules(): array
    {
        return [
            'family_id' => ['required', 'exists:families,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:50', Rule::unique('beneficiaries', 'national_id')->ignore($this->route('beneficiary'))],
            'gender' => ['required', 'in:male,female'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:today'],
            'relationship_to_head' => ['required', 'string', 'max:100'],
            'beneficiary_type' => ['required', 'in:child,adult,elderly,person_with_disability,caregiver,other'],
            'health_status' => ['nullable', 'string', 'max:5000'],
            'disability_status' => ['nullable', 'boolean'],
            'education_status' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
