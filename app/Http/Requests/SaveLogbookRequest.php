<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Closure;

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
                function (string $attribute, mixed $value, Closure $fail) {
                    $dayCurrent = now()->format('d');
                    if (now()->format('H') <= 6) {
                        $dayCurrent = now()->subDay()->format('d');
                    }

                    if (date("d", strtotime($value)) === $dayCurrent) {
                        $fail("No se puede realizar reservas en el mismo dia que van a entrar, mejor realiza un alquiler de habitación normal.");
                    }
                }
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
            'data.relationships.bedrooms.data.*.id' => [
                'required',
                'exists:bedrooms,name',
            ],
            'data.relationships.bedrooms' => []
        ];
    }
}
