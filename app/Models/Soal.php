<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    public function ujian(){
        return $this->belongsTo(Ujian::class);
    }
    public function jawabans(){
        return $this->hasMany(Jawaban::class);
    }
}
