<?php

namespace App\Services;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RoleService
{
    /**
     * List permissions data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mapPermissions(): Collection
    {
        return Permission::orderBy('id')->get()->map(fn ($permission) => [
            'id' => $permission->id,
            'name' => $permission->name
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
            'roles' =>
            Role::where('name', 'like', '%' . $request->input('search') . '%')
                ->orderBy('id')
                ->paginate(20)
                ->appends($request->only('search'))
                ->through(fn ($role) => [
                    'id' => $role->id,
                    'name' => $role->name
                ]),
            'filters' => $request->only('search'),
            'can' => [
                'createRoles' => $request->user()->permissions->contains('name', 'createRoles'),
                'updateRoles' => $request->user()->permissions->contains('name', 'updateRoles'),
                'deleteRoles' => $request->user()->permissions->contains('name', 'deleteRoles')
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
            'permissions' => $this->mapPermissions()
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\RoleStoreRequest $request
     * @return \App\Models\Role
     */
    public function store(RoleStoreRequest $request): Role
    {
        $role = Role::create([
            'name' => $request->name
        ]);

        $role->permissions()->attach($request->permissions);

        return $role;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Role $role
     * @return array
     */
    public function edit(Role $role): array
    {
        return [
            'permissions' => $this->mapPermissions(),
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions()->pluck('id')->toArray()
            ]
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\RoleUpdateRequest $request
     * @param \App\Models\Role $role
     * @return \App\Models\Role
     */
    public function update(RoleUpdateRequest $request, Role $role): Role
    {
        if (($request->name !== $role->name) && Role::where('name', $request->name)->first()) {
            $request->validate(['name' => ['required', 'string', 'unique:roles']]);
        }

        $role->update([
            'name' => $request->name
        ]);

        $role->permissions()->sync($request->permissions);

        return $role;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Role $role
     */
    public function destroy(Role $role)
    {
        $role->delete();
    }
}
