<?php

namespace App\Http\Requests\User;

use App\Http\Requests\FormRequest;

class GetUsersPaginatedRequest extends FormRequest 
{
    protected function prepareForValidation()
    {
        $this->merge([
            'per_page' => $this->input('per_page', 100),
            'page' => $this->input('page', 1),
        ]);
    }

    public function authorize(): bool 
    {
        return true;
    }

    public function rules(): array 
    {
        return [
            'per_page' => ['integer', 'min:1', 'max:500'],
            'page' => ['integer', 'min:1'],
        ];
    }
}
