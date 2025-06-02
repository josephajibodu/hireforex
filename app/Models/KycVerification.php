<?php

namespace App\Models;

use App\Enums\KYCDocumentType;
use App\Enums\KYCStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class KycVerification extends Model
{
    protected $guarded = ['id'];

    protected $table = "kyc_verification";

    protected function casts(): array
    {
        return [
            'status' => KYCStatus::class,
            'document_type' => KYCDocumentType::class
        ];
    }

    public function isPending(): bool
    {
        return $this->status === KYCStatus::Pending;
    }

    public function isCompleted(): bool
    {
        return $this->status === KYCStatus::Completed;
    }

    public function isRejected(): bool
    {
        return $this->status === KYCStatus::Rejected;
    }

    public function canUpdate(): bool
    {
        return  $this->isRejected();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
