<?php

namespace App\Http\Controllers;

use App\Models\Civility;
use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', User::class);

        return Inertia::render('Users/Index', [
            'users' =>
            User::when(
                $request->input('search'),
                fn ($query, $search) =>
                $query
                    ->where('email', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
            )
                ->paginate(20)
                ->appends($request->only('search'))
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'last_name' => $user->last_name,
                    'first_name' => $user->first_name,
                    'email' => $user->email
                ]),
            'filters' => $request->only('search'),
            'can' => [
                'createUsers' => $request->user()->permissions->contains('name', 'createUsers'),
                'updateUsers' => $request->user()->permissions->contains('name', 'updateUsers'),
                'deleteUsers' => $request->user()->permissions->contains('name', 'deleteUsers')
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
        $this->authorize('create', User::class);

        return Inertia::render('Users/Create', [
            'civilities' => Civility::all()->map(fn ($civility) => [
                'id' => $civility->id,
                'name' => $civility->name
            ]),
            'roles' => Role::all()->map(fn ($civility) => [
                'id' => $civility->id,
                'name' => $civility->name
            ]),
            'groups' => Group::all()->map(fn ($civility) => [
                'id' => $civility->id,
                'name' => $civility->name
            ])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $request->validate([
            'last_name' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'civility' => ['required', 'integer', 'in:1,2'],
            'role' => ['required', 'integer']
        ]);

        $user = User::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'email' => $request->email,
            'password' => Hash::make('password'),
            'civility_id' => $request->civility,
            'role_id' => $request->role
        ]);

        $user->groups()->attach(array_column($request->groups, 'id'));
        $role = Role::where('id', $request->role)->first();
        $permissions = $role->permissions()->pluck('id')->toArray();
        $user->permissions()->attach($permissions);

        return to_route('users.index')->with('success', "L'utilisateur $user->first_name $user->last_name a été créé avec succès");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Inertia\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Inertia\Response
     */
    public function edit(User $user): Response
    {
        $this->authorize('update', $user);

        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'last_name' => $user->last_name,
                'first_name' => $user->first_name,
                'email' => $user->email,
                'civility_id' => $user->civility_id,
                'role_id' => $user->role_id,
                'groups' => $user->groups()->pluck('id')->toArray()
            ],
            'civilities' => Civility::all()->map(fn ($civility) => [
                'id' => $civility->id,
                'name' => $civility->name
            ]),
            'roles' => Role::all()->map(fn ($civility) => [
                'id' => $civility->id,
                'name' => $civility->name
            ]),
            'groups' => Group::all()->map(fn ($civility) => [
                'id' => $civility->id,
                'name' => $civility->name
            ])
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        if (($request->email !== $user->email) && User::where('email', $request->email)->first()) $request->validate(['email' => ['required', 'email', 'unique:users']]);

        $request->validate([
            'last_name' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'civility' => ['required', 'integer', 'in:1,2'],
            'role' => ['required', 'integer']
        ]);

        $user->update([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'email' => $request->email,
            'civility_id' => $request->civility,
            'role_id' => $request->role
        ]);

        if ($request->password) $user->update(['password' => Hash::make($request->password)]);

        $user->groups()->attach(array_column($request->groups, 'id'));
        $role = Role::where('id', $request->role)->first();
        $permissions = $role->permissions()->pluck('id')->toArray();
        $user->permissions()->attach($permissions);

        return to_route('users.index')->with('success', "L'utilisateur $user->first_name $user->last_name a été modifié avec succès");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->groups()->detach($user->groups()->pluck('id')->toArray());
        $user->permissions()->detach($user->permissions()->pluck('id')->toArray());
        $user->delete();

        return to_route('users.index')->with('success', "L'utilisateur $user->first_name $user->last_name a été supprimé avec succès");
    }

    /**
     * Import a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request): RedirectResponse
    {
        try {
            fastexcel()->import($request->file('usersFile'), function ($row) {
                $user = User::create([
                    'last_name' => $row['last_name'],
                    'first_name' => $row['first_name'],
                    'email' => $row['email'],
                    'password' => Hash::make($row['password']),
                    'civility_id' => $row['civility_id'],
                    'role_id' => $row['role_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $role = Role::where('id', $row['role_id'])->first();
                $user->permissions()->attach($role->permissions->pluck('id')->toArray());

                return $user;
            });
        } catch (Exception $exception) {
            switch ($exception->getCode()) {
                case '0':
                    return redirect()->back()->with('error', "Erreur lors de l'import : fichier invalide");
                    break;
                case '23000':
                    return redirect()->back()->with('error', "Erreur lors de l'import : champ duppliqué");
                    break;
                default:
                    return redirect()->back()->with('error', "Erreur lors de l'import");
                    break;
            }
        }

        return to_route('users.index')->with('success', 'Les utilisateurs ont été importés avec succès');
    }

    /**
     * Export a resource from storage.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|string
     */
    public function export(): StreamedResponse|string
    {
        return fastexcel(User::select('last_name', 'first_name', 'email', 'civility_id', 'role_id', 'created_at', 'updated_at')->get())->download('users.xlsx');
    }
}
