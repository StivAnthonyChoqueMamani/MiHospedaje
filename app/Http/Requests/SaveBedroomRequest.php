<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveBedroomRequest extends FormRequest
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
            'data.attributes.name' => ['required', Rule::unique('bedrooms', 'name')->ignore($this->route('bedroom'))],
            'data.attributes.description' => ['required'],
            'data.attributes.price' => ['required', 'numeric', 'min:0'],
            'data.attributes.observation' => [],
        ];
    }
}
