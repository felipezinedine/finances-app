<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goals extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = [
        'deadline' => 'date',
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2'
    ];

    public function user () 
    {
        return $this->belongsTo(User::class);
    }
}
