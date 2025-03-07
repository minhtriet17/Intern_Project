<?php

use App\Http\Controllers\Api\LectureController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('lectures', LectureController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('lectures', [LectureController::class, 'index']);
Route::post('lectures', [LectureController::class, 'store']);
Route::get('lectures/{id}', [LectureController::class, 'show']);
Route::get('lectures/{id}/edit', [LectureController::class, 'edit']);
Route::put('lectures/{id}/edit', [LectureController::class, 'update']);
Route::delete('lectures/{id}/delete', [LectureController::class,'destroy']);