<?php

namespace App\Services;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Civility;
use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * List civilities data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mapCivilities(): Collection
    {
        return Civility::orderBy('id')->get()->map(fn ($civility) => [
            'id' => $civility->id,
            'name' => $civility->name
        ]);
    }

    /**
     * List groups data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mapGroups(): Collection
    {
        return Group::orderBy('name')->get()->map(fn ($group) => [
            'id' => $group->id,
            'name' => $group->name
        ]);
    }

    /**
     * List roles data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mapRoles(): Collection
    {
        return Role::orderBy('id')->get()->map(fn ($role) => [
            'id' => $role->id,
            'name' => $role->name
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        return [
            'users' =>
            User::when(
                $request->input('search'),
                fn ($query, $search) => $query
                    ->where('email', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('first_name', 'like', '%' . $search . '%')
            )
                ->orderBy('id')
                ->paginate(20)
                ->appends($request->only('search'))
                ->through(
                    fn ($user) =>
                    [
                        'id' => $user->id,
                        'last_name' => $user->last_name,
                        'first_name' => $user->first_name,
                        'email' => $user->email
                    ]
                ),
            'filters' => $request->only('search'),
            'can' => [
                'createUsers' => $request->user()->permissions->contains('name', 'createUsers'),
                'updateUsers' => $request->user()->permissions->contains('name', 'updateUsers'),
                'deleteUsers' => $request->user()->permissions->contains('name', 'deleteUsers')
            ]
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return array
     */
    public function create(): array
    {
        return [
            'civilities' => $this->mapCivilities(),
            'groups' => $this->mapGroups(),
            'roles' => $this->mapRoles()
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\UserStoreRequest $request
     * @return \App\Models\User
     */
    public function store(UserStoreRequest $request): User
    {
        $user = User::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'email' => $request->email,
            'password' => Hash::make('password'),
            'civility_id' => $request->civility,
            'role_id' => $request->role
        ]);

        $user->groups()->attach($request->groups);
        $role = Role::where('id', $request->role)->first();
        $permissions = $role->permissions()->pluck('id')->toArray();
        $user->permissions()->attach($permissions);

        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function edit(User $user): array
    {
        return [
            'civilities' => $this->mapCivilities(),
            'groups' => $this->mapGroups(),
            'roles' => $this->mapRoles(),
            'user' => [
                'id' => $user->id,
                'last_name' => $user->last_name,
                'first_name' => $user->first_name,
                'email' => $user->email,
                'civility_id' => $user->civility_id,
                'role_id' => $user->role_id,
                'groups' => $user->groups()->pluck('id')->toArray()
            ]
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UserUpdateRequest $request
     * @param \App\Models\User $user
     * @return \App\Models\User
     */
    public function update(UserUpdateRequest $request, User $user): User
    {
        if (($request->email !== $user->email) && User::where('email', $request->email)->first()) {
            $request->validate(['email' => ['required', 'email', 'string', 'unique:users']]);
        }

        $user->update([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'email' => $request->email,
            'civility_id' => $request->civility,
            'role_id' => $request->role
        ]);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->groups()->attach($request->groups);
        $role = Role::where('id', $request->role)->first();
        $permissions = $role->permissions()->pluck('id')->toArray();
        $user->permissions()->attach($permissions);

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     */
    public function destroy(User $user)
    {
        $user->groups()->detach($user->groups()->pluck('id')->toArray());
        $user->permissions()->detach($user->permissions()->pluck('id')->toArray());
        $user->delete();
    }
}
