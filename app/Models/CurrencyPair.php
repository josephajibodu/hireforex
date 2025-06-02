<?php

namespace App\Models;

use App\Enums\BinaryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property string $name
 * @property integer $margin
 * @property integer $trade_duration
 * @property integer $daily_capacity
 * @property integer $current_capacity
 * @property BinaryStatus $status
 */
class CurrencyPair extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => BinaryStatus::class
        ];
    }

    public function trade(): HasMany
    {
        return $this->hasMany(Trade::class);
    }
}
