<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Auth\Access\HandlesAuthorization;

class WithdrawalPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('view withdrawals');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Withdrawal $withdrawal): bool
    {
        return $user->hasRole('admin') ||
               $user->hasPermissionTo('view withdrawals') ||
               $user->id === $withdrawal->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create withdrawals
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Withdrawal $withdrawal): bool
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('update withdrawals');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Withdrawal $withdrawal): bool
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('delete withdrawals');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Withdrawal $withdrawal): bool
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('restore withdrawals');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Withdrawal $withdrawal): bool
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('force delete withdrawals');
    }
}