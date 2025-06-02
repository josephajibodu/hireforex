<?php

namespace App\Models;

use App\Enums\SocialMediaType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $content
 * @property string $image
 * @property SocialMediaType $platform
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SocialMediaPost extends Model
{
    protected $table = "social_media_post";

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            if ($post->image) {
                Storage::delete($post->image);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'platform' => SocialMediaType::class
        ];
    }
}
