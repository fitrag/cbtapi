<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Jawaban, Soal};
use App\Http\Resources\JawabanResource;

class JawabanController extends Controller
{
    public function store(Request $req){
        $kunci = Soal::find($req->soal_id);
        
        $insert = Jawaban::updateOrCreate(
            [
                'soal_id' => $req->soal_id,
                'user_id'   => $req->user_id,
            ],
            [
                'jawaban'   => $req->jawaban,
                'user_id'   => $req->user_id,]
            );

        if($kunci->kunci == $req->jawaban){
            if($insert){
                return new JawabanResource(true, "Berhasil menjawab", [
                    'status'    => 'Benar',
                    'jawaban'   => $req->jawaban,
                    'soal_id'   => $req->soal_id,
                    'skor'      => $kunci->bobot
                ]);
            }

        }else{
            if($insert){
                return new JawabanResource(true, "Berhasil menjawab", [
                    'status'    => 'Salah',
                    'jawaban'   => $req->jawaban,
                    'soal_id'   => $req->soal_id,
                    'skor'      => $kunci->bobot
                ]);
            }
        }
        
    }

    public function show($id, $user_id){
        $jawaban = Jawaban::whereSoalId($id)->whereUserId($user_id)->first();
        return new JawabanResource(true, "jawaban soal ini", $jawaban);
    }
}
