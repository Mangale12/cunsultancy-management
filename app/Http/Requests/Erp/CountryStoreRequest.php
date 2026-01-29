<?php

namespace App\Http\Requests\Erp;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return $this->user()?->can('create', \App\Models\Country::class) ?? false;
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
