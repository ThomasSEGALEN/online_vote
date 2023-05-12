<?php

namespace App\Policies;

use App\Models\LabelSet;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LabelSetPolicy
{
    use HandlesAuthorization;

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
     * @param \App\Models\LabelSet $labelSet
     * @return bool
     */
    public function update(User $user, LabelSet $labelSet): bool
    {
        return $user->permissions->contains('name', 'updateSessions');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\LabelSet $labelSet
     * @return bool
     */
    public function delete(User $user, LabelSet $labelSet): bool
    {
        return $user->permissions->contains('name', 'deleteSessions');
    }
}
