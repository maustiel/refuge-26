<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Species;
use Illuminate\Validation\Rule;

class AnimalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'species' => ['required', Rule::enum(Species::class)],
            'shelter_id' => ['required', 'integer', 'exists:shelters,id'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'description' => ['nullable', 'string', 'max:1000'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'birth_date.before_or_equal' => 'La date de naissance ne peut pas être dans le futur.',
        ];
    }
}
