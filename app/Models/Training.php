<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = ['kode', 'kerusakan_id', 'motor_id', 'data_gejala'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode)) {
                $maxId = self::max('id') ?? 0;
                $model->kode = 'T'.str_pad($maxId + 1, 4, '0', STR_PAD_LEFT);
            }
        });
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
