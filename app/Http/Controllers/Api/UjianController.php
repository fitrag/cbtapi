<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Ujian, Soal, Jawaban};

class UjianController extends Controller
{
    public function index(){
        $ujians = Ujian::with(['soals','kelas'])->get();
        return [
            'data'  => $ujians
        ];
    }
    public function show($id){
        $ujian = Ujian::with(['kelas','soals'])->whereId($id)->first();
        return [
            'data'  => $ujian
        ];
    }
    public function cekToken($id, $token, $user_id){
        $ujian = Ujian::find($id);
        if($token == $ujian->token){
            $soals = Soal::select(['id'])->whereUjianId($id)->get();

            $countJawaban = Jawaban::whereUjianId($id)->whereUserId($user_id)->count();

            if($countJawaban > 1){
                return [
                    'token'         => $ujian->token,
                    'ujian_id'      => $id,
                    'waktu_ujian'   => $ujian->waktu
                ];
            }else{
                for($i=0;$i<$soals->count();$i++){
                    Jawaban::create([
                        'ujian_id'      => $id,
                        'soal_id'       => $soals[$i]->id,
                        'user_id'       => $user_id,
                        'jawaban'       => ''
                    ]);
                }
                return [
                    'token'         => $ujian->token,
                    'ujian_id'      => $id,
                    'waktu_ujian'   => $ujian->waktu
                ];
            }

        }else{
            return [
                'message'   => 'Salah',
                'user_id'   => $user_id
            ];
        }
    }
}
