<?php

namespace App\Http\Requests;


class UploadRequest extends Request
{
    public function rules(): array
    {
        return [
            'files.*' => ['required', 'file']
        ];
    }
}
