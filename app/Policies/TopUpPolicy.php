<?php

namespace App\Policies;

use App\Models\TopUp;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TopUpPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Users can view their own top-ups, admins can view all
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TopUp $topUp): bool
    {
        return $user->id === $topUp->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create top-up requests
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TopUp $topUp): bool
    {
        return $user->hasRole('admin'); // Only admins can update top-up status
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TopUp $topUp): bool
    {
        return $user->hasRole('admin'); // Only admins can delete top-ups
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TopUp $topUp): bool
    {
        return $user->hasRole('admin'); // Only admins can restore top-ups
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TopUp $topUp): bool
    {
        return $user->hasRole('admin'); // Only admins can force delete top-ups
    }
}