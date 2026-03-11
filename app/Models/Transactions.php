<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = ['date' => 'date', 'amount' => 'decimal:2'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Accounts::class);
    }

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
}
