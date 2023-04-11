<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return boolean
     */
    public function viewAny(User $user): bool
    {
        return $user->permissions->contains('name', 'viewAnyGroups');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Group $group
     * @return boolean
     */
    public function view(User $user, Group $group): bool
    {
        return $user->permissions->contains('name', 'viewGroups');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return boolean
     */
    public function create(User $user): bool
    {
        return $user->permissions->contains('name', 'createGroups');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Group $group
     * @return boolean
     */
    public function update(User $user, Group $group): bool
    {
        return $user->permissions->contains('name', 'updateGroups');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Group $group
     * @return boolean
     */
    public function delete(User $user, Group $group): bool
    {
        return $user->permissions->contains('name', 'deleteGroups');
    }
}
