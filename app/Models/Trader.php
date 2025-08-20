<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property int $experience_years
 * @property string $favorite_pairs
 * @property string $track_record
 * @property float $mbg_rate
 * @property float $potential_return
 * @property float $min_capital
 * @property float $available_volume
 * @property int $duration_days
 * @property bool $is_available
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Trader extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'experience_years',
        'favorite_pairs',
        'track_record',
        'mbg_rate',
        'potential_return',
        'min_capital',
        'available_volume',
        'duration_days',
        'is_available',
    ];

    protected $casts = [
        'mbg_rate' => 'decimal:2',
        'potential_return' => 'decimal:2',
        'min_capital' => 'decimal:2',
        'available_volume' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /**
     * Check if trader has sufficient volume for a trade
     */
    public function hasSufficientVolume(float $amount): bool
    {
        return $this->available_volume >= $amount;
    }

    /**
     * Check if trader accepts the given capital amount
     */
    public function acceptsCapital(float $amount): bool
    {
        return $amount >= $this->min_capital;
    }

    /**
     * Get all trades for this trader
     */
    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class);
    }

    /**
     * Get active trades for this trader
     */
    public function activeTrades(): HasMany
    {
        return $this->hasMany(Trade::class)->where('status', 'active');
    }

    /**
     * Get completed trades for this trader
     */
    public function completedTrades(): HasMany
    {
        return $this->hasMany(Trade::class)->where('status', 'completed');
    }

    /**
     * Get the track record as an array
     */
    public function getTrackRecordArrayAttribute(): array
    {
        return str_split($this->track_record);
    }

    /**
     * Get the win rate percentage
     */
    public function getWinRateAttribute(): float
    {
        $wins = substr_count($this->track_record, 'W');
        $total = strlen($this->track_record);

        return $total > 0 ? ($wins / $total) * 100 : 0;
    }
}
