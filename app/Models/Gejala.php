<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gejala extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama_gejala',
        'sistem_pembakaran',
        'keterangan',
    ];
}
