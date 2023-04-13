<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupStoreRequest;
use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class GroupController extends Controller
{
    public function __construct(private GroupService $groupService)
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
        $this->authorize('viewAny', Group::class);

        return inertia('Groups/Index', $this->groupService->index($request));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(): Response
    {
        $this->authorize('create', Group::class);

        return inertia('Groups/Create', $this->groupService->create());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\GroupStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(GroupStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Group::class);

        $group = $this->groupService->store($request);

        return to_route('groups.index')->with('success', "Le groupe $group->name a été créé avec succès");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group $group
     * @return \Inertia\Response
     */
    public function edit(Group $group): Response
    {
        $this->authorize('update', $group);

        return inertia('Groups/Edit', $this->groupService->edit($group));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Group $group): RedirectResponse
    {
        $this->authorize('update', $group);

        $group = $this->groupService->update($request, $group);

        return to_route('groups.index')->with('success', "Le groupe $group->name a été modifié avec succès");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group $group
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Group $group): RedirectResponse
    {
        $this->authorize('delete', $group);

        $this->groupService->destroy($group);

        return to_route('groups.index')->with('success', "Le groupe $group->name a été supprimé avec succès");
    }
}
