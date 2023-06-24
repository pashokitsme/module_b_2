<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserController extends Controller
{
    public function login(LoginRequest $req) {
        if (!$user = User::where("email", $req->email)->where("password", $req->password)->first())
            throw new UnauthorizedHttpException("login");

        $user->token = Str::random();
        $user->save();
        return $this->json(["success" => true, "message" => "Success", "token" => $user->token]);
    }

    public function register(RegisterRequest $req) {
        $user = User::create($req->merge(["name" => $req->first_name . ' ' . $req->last_name])->all());
        $user->token = Str::random();
        return $this->json(["success" => true, "message" => "Success", "token" => $user->token]);
    }

    public function logout(Request $req) {
        $req->user->token = null;
        $req->user->save();
        return $this->json(["success" => true, "message" => "Logout"]);
    }
}
