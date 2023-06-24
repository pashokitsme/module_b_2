<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\GetFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post("authorization", [UserController::class, "login"]);
Route::post("registration", [UserController::class, "register"]);
Route::post("logout", [UserController::class, "logout"]);


Route::prefix("files")->middleware("auth")->group(function() {
   Route::post("/", [FileController::class, "upload"]);
    Route::get('/disk', [FileController::class, 'listOwnedFiles']);
    Route::get('/shared', [FileController::class, 'listAccessedFiles']);
   Route::get("/{hash}", [FileController::class, "index"]);


   Route::prefix('/{hash}')->middleware(GetFile::class)->group(function() {
       Route::patch('/', [FileController::class, 'rename']);
       Route::delete('/', [FileController::class, 'delete']);
       Route::get('/access', [FileController::class, 'accesses']);
       Route::post('/access', [FileController::class, 'grant']);
       Route::delete('/access', [FileController::class, 'forbid']);
   });


});
