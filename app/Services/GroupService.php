<?php

namespace App\Services;

use App\Http\Requests\GroupStoreRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GroupService
{
    /**
     * List users data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mapUsers(): Collection
    {
        return User::orderBy('last_name')->orderBy('first_name')->get()->map(fn ($user) => [
            'id' => $user->id,
            'name' => $user->last_name . ' ' . $user->first_name,
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
            'groups' =>
            Group::when(
                $request->input('search'),
                fn ($query, $search) =>
                $query->where('name', 'like', '%' . $search . '%')
            )
                ->orderBy('id')
                ->paginate(20)
                ->appends($request->only('search'))
                ->through(fn ($group) => [
                    'id' => $group->id,
                    'name' => $group->name
                ]),
            'filters' => $request->only('search'),
            'can' => [
                'createGroups' => $request->user()->permissions->contains('name', 'createGroups'),
                'deleteGroups' => $request->user()->permissions->contains('name', 'deleteGroups'),
                'updateGroups' => $request->user()->permissions->contains('name', 'updateGroups')
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
            'users' => $this->mapUsers()
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\GroupStoreRequest $request
     * @return \App\Models\Group
     */
    public function store(GroupStoreRequest $request): Group
    {
        $group = Group::create([
            'name' => $request->name
        ]);

        $group->users()->attach(array_column($request->users, 'id'));

        return $group;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Group $group
     * @return array
     */
    public function edit(Group $group): array
    {
        return [
            'group' => [
                'id' => $group->id,
                'name' => $group->name
            ],
            'users' => $this->mapUsers()
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Group $group
     * @return \App\Models\Group
     */
    public function update(Request $request, Group $group): Group
    {
        if (($request->name !== $group->name) && Group::where('name', $request->name)->first()) {
            $request->validate(['name' => ['required', 'string', 'unique:groups']]);
        }

        $group->update([
            'name' => $request->name
        ]);

        $group->users()->sync(array_column($request->users, 'id'));

        return $group;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Group $group
     * @return void
     */
    public function destroy(Group $group)
    {
        $group->users()->detach($group->users()->pluck('id')->toArray());
        $group->delete();
    }
}
