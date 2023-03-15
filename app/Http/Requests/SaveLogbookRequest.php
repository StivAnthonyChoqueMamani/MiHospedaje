<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveLogbookRequest extends FormRequest
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
            'data.attributes.entry_at' => [
                Rule::requiredIf(
                    $this->data['attributes']['reservation'] === true
                ),
                'date',
            ],
            'data.attributes.exit_at' => [
                Rule::requiredIf(
                    $this->data['attributes']['reservation'] === true
                ),
                'date',
            ],
            'data.attributes.reservation' => ['boolean', 'required'],
            'data.attributes.observation' => [],
            'data.relationships.customer.data.id' => [
                Rule::requiredIf(
                    !($this->method() === 'PATCH')
                ),
                'exists:customers,dni'
            ],
        ];
    }
}
