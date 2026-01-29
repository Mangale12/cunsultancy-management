<?php

namespace App\Http\Requests\Erp;

use App\Models\State;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StateUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // $state = $this->route('state');
        // if (! $state instanceof State) {
        //     return false;
        // }

        // return $this->user()?->can('update', $state) ?? false;
        return true;
    }

    public function rules(): array
    {
        $state = $this->route('state');

        return [
            'country_id' => ['required', 'exists:countries,id'],
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
