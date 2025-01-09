<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{UserController, SoalController, JawabanController, AuthController};

Route::get('/user', function(Request $req){
    return response()->json($req->user());
})->middleware('auth:sanctum');
// Route::apiResource('/users', UserController::class);
Route::apiResource('/soals', SoalController::class);
Route::apiResource('/jawaban', JawabanController::class);

Route::post("/register", [AuthController::class,'register']);
Route::post("/login", [AuthController::class,'login']);
