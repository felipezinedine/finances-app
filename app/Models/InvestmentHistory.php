<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentHistory extends Model
{
    public $timestamps = false;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = [
        'recoreded' => 'datetime',
        'value' => 'decimal:2'
    ];

    public function user () 
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressAttribute(): float
    {
        if ($this->target_amount == 0) return 0;

        return min(($this->current_amount / $this->target_amount) * 100, 100);
    }
}
