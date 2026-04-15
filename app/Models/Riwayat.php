<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kerusakan_id',
        'gejala_dipilih',
        'confidence',
        'motor_id',
        'sistem_pembakaran'
    ];

    protected $casts = [
        'gejala_dipilih' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kerusakan()
    {
        return $this->belongsTo(Kerusakan::class);
    }

    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }
}
