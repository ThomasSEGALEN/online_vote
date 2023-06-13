<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
        return $user->permissions->contains('name', 'viewAnyUsers');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->permissions->contains('name', 'viewUsers');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->permissions->contains('name', 'createUsers');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function update(User $user): bool
    {
        return $user->permissions->contains('name', 'updateUsers');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->permissions->contains('name', 'deleteUsers');
    }
}
