<?php

namespace App\Http\Requests\Erp;

use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $branch = $this->route('branch');
        if (! $branch instanceof Branch) {
            return false;
        }

        return $this->user()?->can('update', $branch) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $branchId = $this->route('branch')->id;

        return [
            'country_id' => ['required', 'exists:countries,id'],
            'state_id' => ['required', 'exists:states,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', Rule::unique('branches', 'code')->ignore($branchId)],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'manager_name' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'country_id.required' => 'Country is required.',
            'country_id.exists' => 'Selected country is invalid.',
            'state_id.required' => 'State is required.',
            'state_id.exists' => 'Selected state is invalid.',
            'name.required' => 'Branch name is required.',
            'name.max' => 'Branch name may not be greater than 255 characters.',
            'code.required' => 'Branch code is required.',
            'code.max' => 'Branch code may not be greater than 10 characters.',
            'code.unique' => 'Branch code has already been taken.',
            'address.max' => 'Address may not be greater than 500 characters.',
            'phone.max' => 'Phone number may not be greater than 20 characters.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email may not be greater than 255 characters.',
            'manager_name.max' => 'Manager name may not be greater than 255 characters.',
        ];
    }
}
