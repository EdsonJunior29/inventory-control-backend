<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'model',
        'model_id',
        'action',
        'changes',
        'created_at'
    ];

    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime'
    ];
}