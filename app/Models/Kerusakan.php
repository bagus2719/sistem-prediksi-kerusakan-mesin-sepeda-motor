<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kerusakan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama_kerusakan',
        'solusi',
    ];
}
