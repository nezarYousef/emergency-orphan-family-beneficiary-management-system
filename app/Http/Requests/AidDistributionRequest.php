<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AidDistributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'data_entry'], true);
    }

    public function rules(): array
    {
        return [
            'family_id' => ['nullable', 'required_without:beneficiary_id', 'exists:families,id'],
            'beneficiary_id' => ['nullable', 'required_without:family_id', 'exists:beneficiaries,id'],
            'aid_type' => ['required', 'string', 'max:100'],
            'distribution_date' => ['required', 'date'],
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'provider_organization' => ['nullable', 'string', 'max:255'],
            'reference_number' => ['nullable', 'string', 'max:100', Rule::unique('aid_distributions', 'reference_number')->ignore($this->route('aidDistribution'))],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
