<?php

namespace App\Policies;

use App\Models\GiftCard;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GiftCardPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true; // Anyone can view gift cards
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, GiftCard $giftCard): bool
    {
        return true; // Anyone can view gift card details
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
        return $user->hasRole('admin'); // Only admins can create gift cards
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GiftCard $giftCard): bool
    {
        return true;
        return $user->hasRole('admin'); // Only admins can update gift cards
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GiftCard $giftCard): bool
    {
        return true;
        return $user->hasRole('admin'); // Only admins can delete gift cards
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GiftCard $giftCard): bool
    {
        return true;
        return $user->hasRole('admin'); // Only admins can restore gift cards
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GiftCard $giftCard): bool
    {
        return true;
        return $user->hasRole('admin'); // Only admins can force delete gift cards
    }
}