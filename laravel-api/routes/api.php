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
Route::get('lecture/{id}', [LectureController::class, 'show']);
Route::put('lecture/{id}/edit', [LectureController::class, 'update']);
Route::delete('lecture/{id}/delete', [LectureController::class,'delete']);