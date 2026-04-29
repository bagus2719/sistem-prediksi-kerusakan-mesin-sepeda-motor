<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    protected $fillable = ['merk', 'nama_motor', 'sistem_pembakaran'];
}
