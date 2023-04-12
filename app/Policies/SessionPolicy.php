<?php

namespace App\Policies;

use App\Models\Session;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SessionPolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return boolean
     */
    public function viewAny(User $user): bool
    {
        return $user->permissions->contains('name', 'viewAnySessions');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Session $session
     * @return boolean
     */
    public function view(User $user, Session $session): bool
    {
        return $user->permissions->contains('name', 'viewSessions');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user): bool
    {
        return $user->permissions->contains('name', 'createSessions');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Session $session
     * @return boolean
     */
    public function update(User $user, Session $session): bool
    {
        return $user->permissions->contains('name', 'updateSessions');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Session $session
     * @return boolean
     */
    public function delete(User $user, Session $session): bool
    {
        return $user->permissions->contains('name', 'deleteSessions');
    }
}