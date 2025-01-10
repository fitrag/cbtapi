<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Soal;
use App\Http\Resources\SoalResource;

class SoalController extends Controller
{
    public function index(Request $req){
        $soals = Soal::whereUjianId($req->ujian_id)->paginate(1);

        return new SoalResource(true, "List soal", $soals);
    }
}
