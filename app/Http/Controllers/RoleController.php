<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class RoleController extends Controller
{
    public function __construct(private RoleService $roleService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Role::class);

        return inertia('Roles/Index', $this->roleService->index($request));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(): Response
    {
        $this->authorize('create', Role::class);

        return inertia('Roles/Create', $this->roleService->create());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\RoleStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RoleStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Role::class);

        $role = $this->roleService->store($request);

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

        return inertia('Roles/Edit', $this->roleService->edit($role));
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

        $role = $this->roleService->update($request, $role);

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

        $this->roleService->destroy($role);

        return to_route('roles.index')->with('success', "Le rôle $role->name a été supprimé avec succès");
    }
}
