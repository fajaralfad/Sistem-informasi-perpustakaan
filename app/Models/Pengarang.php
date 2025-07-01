<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengarang extends Model
{
     protected $fillable = ['nama', 'email', 'alamat'];

     // Di app/Models/Pengarang.php
public function bukus()
{
    return $this->hasMany(Buku::class);
}
}


