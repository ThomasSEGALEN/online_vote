<?php

namespace App\Policies;

use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class SessionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->permissions->contains('name', 'viewAnySessions');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Session $session
     * @return bool
     */
    public function view(User $user, Session $session): bool
    {
        $sessionInProgress =  $session->start_date || $session->end_date ? $session->start_date <= Carbon::now() && $session->end_date >= Carbon::now() : true;
        $sessionOpen = $session->status->id == 1 ? true : false;

        return $user->permissions->contains('name', 'viewSessions') || $user->sessions->contains('id', $session->id) && $sessionInProgress && $sessionOpen;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->permissions->contains('name', 'createSessions');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function update(User $user): bool
    {
        return $user->permissions->contains('name', 'updateSessions');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->permissions->contains('name', 'deleteSessions');
    }
}
