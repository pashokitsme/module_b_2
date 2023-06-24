<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use ValidatesRequests;

    public function json($data, $code = 200) {
        return response()->json($data, $code);
    }
}
