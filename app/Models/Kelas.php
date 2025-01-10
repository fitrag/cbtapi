<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    public function users(){
        return $this->hasMany(User::class);
    }
    public function ujians(){
        return $this->hasMany(Ujian::class);
    }
}
