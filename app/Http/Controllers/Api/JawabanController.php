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
            [ 'soal_id' => $req->soal_id],
            [
            'jawaban'   => $req->jawaban,
        ]);
        if($insert){
            return new JawabanResource(true, "Berhasil menjawab", [
                'jawaban'   => $req->jawaban,
                'soal_id'   => $req->soal_id,
            ]);
        }
    }

    public function show($id){
        $jawaban = Jawaban::whereSoalId($id)->first();
        return new JawabanResource(true, "jawaban soal ini", $jawaban);
    }
}
