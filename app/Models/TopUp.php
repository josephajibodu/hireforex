<?php

namespace App\Models;

use App\Enums\TopupMethod;
use App\Enums\TopupStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopUp extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'payment_method' => TopupMethod::class,
            'status' => TopupStatus::class
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}