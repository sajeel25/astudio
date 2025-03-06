<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TimeSheetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::middleware('auth:api')->group(function () {
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('timesheets', TimeSheetController::class);
    Route::apiResource('attributes', AttributeController::class);
    Route::get('attribute-values/{entity_id}', [AttributeValueController::class, 'index']);
    Route::delete('attribute-values/{id}', [AttributeValueController::class, 'destroy']);
    Route::post('logout', function (Request $request) {
        $request->user()->token()->revoke();
        return response()->json(['status_code' => 200, 'message' => 'Logged out successfully'], 200);
    });

});

