<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HouseController;
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
Route::group([
    'middleware' => 'api',
], function () {
    // Define your API routes here
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->name('login');

    

});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/houses', [HouseController::class, 'getUserHouses']);
    Route::post('/houses', [HouseController::class, 'store']); 
    Route::post('/houses/{house}/images', [HouseController::class, 'addImage']);
    Route::delete('/houses/{house}', [HouseController::class, 'destroy']); 
    Route::delete('/houses/{house}/images/{image}', [HouseController::class, 'deleteImage']); 
});



Route::middleware('auth:sanctum')->
    get('/user', function (Request $request) {
        return $request->user();
    });
