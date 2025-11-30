<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Users can see their own profile, admins can see all users
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins can create new users
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Users can update themselves, admins can update anyone
        return $user->id === $model->id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Only admins can delete users, but not themselves
        return $user->role === 'admin' && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->role === 'admin' && $user->id !== $model->id;
    }

    /**
     * Determine if the user can change roles.
     */
    public function changeRole(User $user, User $model): bool
    {
        // Admin cannot change their own role
        return $user->role === 'admin' && $user->id !== $model->id;
    }

    /**
     * Determine if the user can update passwords.
     */
    public function updatePassword(User $user, User $model): bool
    {
        // Users can update their own password, admins can update any password
        return $user->id === $model->id || $user->role === 'admin';
    }

    /**
     * Determine if the user can access admin panel.
     */
    public function accessAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }
}
