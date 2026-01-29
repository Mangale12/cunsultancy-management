<?php

namespace App\Http\Requests\Erp;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StateStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return $this->user()?->can('create', \App\Models\State::class) ?? false;
        return true;
    }

    public function rules(): array
    {
        return [
            'country_id' => ['required', 'exists:countries,id'],
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
