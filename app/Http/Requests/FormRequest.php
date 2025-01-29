<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Support\Facades\Response;  
use Illuminate\Validation\ValidationException;

abstract class FormRequest extends BaseFormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $response = Response::validationError($validator->errors()->getMessages());

        throw new ValidationException($validator, $response);
    }
}