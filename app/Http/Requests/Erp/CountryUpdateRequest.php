<?php

namespace App\Http\Requests\Erp;

use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // $country = $this->route('country');
        // if (! $country instanceof Country) {
        //     return false;
        // }

        // return $this->user()?->can('update', $country) ?? false;
        return true;
    }

    public function rules(): array
    {
        $country = $this->route('country');

        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
