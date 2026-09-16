<?php

namespace App\Http\Requests;

use App\Models\Beneficiary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrphanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'data_entry'], true);
    }

    public function rules(): array
    {
        return [
            'beneficiary_id' => ['required', 'exists:beneficiaries,id', Rule::unique('orphans', 'beneficiary_id')->ignore($this->route('orphan'))],
            'family_id' => ['required', 'exists:families,id'],
            'orphan_status' => ['required', 'string', 'max:50'],
            'father_status' => ['required', 'string', 'max:50'],
            'mother_status' => ['required', 'string', 'max:50'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_relationship' => ['nullable', 'string', 'max:100'],
            'school_status' => ['nullable', 'string', 'max:100'],
            'sponsorship_status' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $beneficiary = Beneficiary::find($this->integer('beneficiary_id'));
            if (! $beneficiary) {
                return;
            }
            if ($beneficiary->family_id !== $this->integer('family_id')) {
                $validator->errors()->add('beneficiary_id', 'The beneficiary must belong to the selected family.');
            }
            if ($beneficiary->beneficiary_type !== 'child') {
                $validator->errors()->add('beneficiary_id', 'Only beneficiaries classified as children can have an orphan record.');
            }
        });
    }
}
