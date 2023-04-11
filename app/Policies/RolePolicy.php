<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
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
        return $user->permissions->contains('name', 'viewAnyRoles');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Role $role
     * @return boolean
     */
    public function view(User $user, Role $role): bool
    {
        return $user->permissions->contains('name', 'viewRoles');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return boolean
     */
    public function create(User $user): bool
    {
        return $user->permissions->contains('name', 'createRoles');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Role $role
     * @return boolean
     */
    public function update(User $user, Role $role): bool
    {
        return $user->permissions->contains('name', 'updateRoles');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Role $role
     * @return boolean
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->permissions->contains('name', 'deleteRoles');
    }
}
