<?php

namespace App\Traits;

use App\Models\KycVerification;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasKycVerification
{
    public function kyc(): HasOne
    {
        return $this->hasOne(KycVerification::class);
    }
}
