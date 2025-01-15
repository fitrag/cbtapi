<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    protected $guarded = [''];

    public function soal(){
        return $this->belongsTo(Soal::class);
    }
}
