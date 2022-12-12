<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\resetController;
use App\Http\Controllers\signUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::post('/sign-up', [signUp::class, 'signUp']);
Route::post('/verify-email/{token}', [signUp::class, 'verifyEmail']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/forget-password', [resetController::class, 'forgetPassword']);
Route::post('/reset-password/{token}', [resetController::class, 'resetPassword']);

$router->group(['middleware' => 'auth_user'], function () use ($router) {
    Route::post('/user', [LoginController::class, 'userData']);
    Route::post('/logout', [LoginController::class, 'logout']);
});
