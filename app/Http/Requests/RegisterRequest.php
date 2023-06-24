<?php

namespace App\Http\Requests;


class RegisterRequest extends Request
{
    public function rules(): array
    {
        return [
            "first_name" => ["required", "string"],
            "last_name" => ["required", "string"],
            "email" => ["required", "string", "unique:users"],
            "password" => ["required", "string", "min:3", "regex:/[a-z]/", "regex:/[A-Z]/", "regex:/[0-9]/"]
        ];
    }
}
