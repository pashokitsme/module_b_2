<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class Request extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json(['success' => false, "message" => $validator->errors()], 422));
    }
}
