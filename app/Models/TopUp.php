<?php

namespace App\Models;

use App\Enums\TopupMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TopUp extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'payment_method' => TopupMethod::class
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}