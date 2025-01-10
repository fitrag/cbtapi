<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    public function soals(){
        return $this->hasMany(Soal::class);
    }
    public function kelas(){
        return $this->belongsTo(Kelas::class);
    }
}
