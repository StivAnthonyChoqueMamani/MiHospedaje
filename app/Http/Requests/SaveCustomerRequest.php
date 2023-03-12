<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.name' => ['required'],
            'data.attributes.lastname' => ['required'],
            'data.attributes.dni' => ['required', Rule::unique('customers', 'dni')->ignore($this->route('customer'))],
            'data.attributes.observation' => [],
        ];
    }
}
