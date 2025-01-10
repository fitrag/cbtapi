<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ujian;

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
    public function cekToken($id){
        $ujian = Ujian::find($id);
        return [
            'token'  => $ujian->token
        ];
    }
}
