<?php

namespace App\Policies;

use App\Enums\SystemPermissions;
use App\Models\KycVerification;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KycVerificationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(SystemPermissions::ManageKYC);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KycVerification $kycVerification): bool
    {
        return $user->hasPermissionTo(SystemPermissions::ManageKYC);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(SystemPermissions::ManageKYC);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KycVerification $kycVerification): bool
    {
        return $user->hasPermissionTo(SystemPermissions::ManageKYC);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KycVerification $kycVerification): bool
    {
        return $user->hasPermissionTo(SystemPermissions::ManageKYC);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KycVerification $kycVerification): bool
    {
        return $user->hasPermissionTo(SystemPermissions::ManageKYC);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KycVerification $kycVerification): bool
    {
        return $user->hasPermissionTo(SystemPermissions::ManageKYC);
    }
}
