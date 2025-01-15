<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jawaban;
use App\Http\Resources\JawabanResource;

class JawabanController extends Controller
{
    public function store(Request $req){
        $insert = Jawaban::updateOrCreate(
            [
                'soal_id' => $req->soal_id,
                'user_id'   => $req->user_id,
            ],
            [
                'jawaban'   => $req->jawaban,
                'user_id'   => $req->user_id,]
            );
        if($insert){
            return new JawabanResource(true, "Berhasil menjawab", [
                'jawaban'   => $req->jawaban,
                'soal_id'   => $req->soal_id,
            ]);
        }
    }

    public function show($id, $user_id){
        $jawaban = Jawaban::whereSoalId($id)->whereUserId($user_id)->first();
        return new JawabanResource(true, "jawaban soal ini", $jawaban);
    }
}
