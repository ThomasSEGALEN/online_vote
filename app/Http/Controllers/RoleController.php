<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Role::class);

        return inertia('Roles/Index', [
            'roles' =>
            Role::when(
                $request->input('search'),
                fn ($query, $search) =>
                $query->where('name', 'like', '%' . $search . '%')
            )
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
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(): Response
    {
        $this->authorize('create', Role::class);

        return inertia('Roles/Create', [
            'permissions' => Permission::all()->map(fn ($permission) => [
                'id' => $permission->id,
                'name' => $permission->name
            ])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Role::class);

        $request->validate([
            'name' => ['required', 'string', 'unique:roles']
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        $role->permissions()->attach($request->permissions);

        return to_route('roles.index')->with('success', "Le rôle $role->name a été créé avec succès");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role $role
     * @return \Inertia\Response
     */
    public function edit(Role $role): Response
    {
        $this->authorize('update', $role);

        return inertia('Roles/Edit', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions()->pluck('id')->toArray()
            ],
            'permissions' => Permission::all()->map(fn ($permission) => [
                'id' => $permission->id,
                'name' => $permission->name
            ])
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $this->authorize('update', $role);

        if (($request->name !== $role->name) && Role::where('name', $request->name)->first()) {
            $request->validate(['name' => ['required', 'string', 'unique:roles']]);
        }

        $role->update([
            'name' => $request->name
        ]);

        $role->permissions()->sync($request->permissions);

        return to_route('roles.index')->with('success', "Le rôle $role->name a été modifié avec succès");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('delete', $role);

        $role->permissions()->detach($role->permissions()->pluck('id')->toArray());
        $role->delete();

        return to_route('roles.index')->with('success', "Le rôle $role->name a été supprimé avec succès");
    }
}
