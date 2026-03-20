<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentHistory extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'recorded_at' => 'datetime',
        'value' => 'decimal:2',
    ];

    public function investment()
    {
        return $this->belongsTo(Investments::class, 'investment_id');
    }
}
