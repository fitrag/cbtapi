<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{UserController, SoalController, JawabanController, AuthController, UjianController};

Route::get('/user', function(Request $req){
    return response()->json($req->user()->load('kelas'));
})->middleware('auth:sanctum');
// Route::apiResource('/users', UserController::class);
Route::apiResource('/soals', SoalController::class);
// Route::apiResource('/jawaban', JawabanController::class);
Route::post("/jawaban", [JawabanController::class,'store']);
Route::get("/jawaban/{id}/{user_id}", [JawabanController::class,'show']);

Route::get("/ujian", [UjianController::class,'index']);
Route::get("/ujian/{id}", [UjianController::class,'show']);
Route::get("/ujian/{id}/token/{token}/{user_id}", [UjianController::class,'cekToken']);


Route::post("/register", [AuthController::class,'register']);
Route::post("/login", [AuthController::class,'login']);
