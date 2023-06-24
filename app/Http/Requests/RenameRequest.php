<?php

namespace App\Http\Requests;


class RenameRequest extends Request
{
    public function rules(): array
    {
        return [
            "name" => ["required", "string"],
        ];
    }
}
