<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investments extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = [
        'date' => 'date',
        'invested_amount' => 'decimal:2',
        'current_value' => 'decimal:2'
    ];

    public function user () 
    {
        return $this->belongsTo(User::class);    
    }

    public function history () 
    {
        return $this->hasMany(InvestmentHistory::class, 'investment_id');
    }

    // lucro / prejuizo calculado
    public function getProfitAttribute(): float
    {
        return $this->current_value - $this->invested_amount;
    }

    public function getProfitPercentAttribute(): float
    {
        if ($this->invested_amount == 0) return 0; 
        return ($this->profif / $this->invested_amount) * 100;
    }
}
