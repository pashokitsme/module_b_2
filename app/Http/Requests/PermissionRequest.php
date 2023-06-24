<?php

namespace App\Http\Requests;


class PermissionRequest extends Request
{
    public function rules(): array
    {
        return [
            "email" => ["required", "string", 'exists:users'],
        ];
    }
}
