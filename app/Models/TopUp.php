<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TopUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'bybit_email',
        'transaction_reference',
        'screenshot_path',
        'status',
        'rejection_reason'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}