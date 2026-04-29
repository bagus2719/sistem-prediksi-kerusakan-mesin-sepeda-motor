<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class C45Model extends Model
{
    protected $table = 'c45_models';
    
    protected $fillable = [
        'tree_data',
        'accuracy',
        'is_active',
    ];

    protected $casts = [
        'tree_data' => 'array',
        'is_active' => 'boolean',
        'accuracy' => 'float',
    ];
}
