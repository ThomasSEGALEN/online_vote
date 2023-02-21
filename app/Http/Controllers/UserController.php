<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Imports\UserImport;
use App\Models\Civility;
use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        return Inertia::render('Users/Index', [
            'users' => 
                User::when($request->input('search'), fn ($query, $search) =>
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return Inertia::render('Users/Create', [
            'civilities' => Civility::all(),
            'roles' => Role::all(),
            'groups' => Group::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function prestore(Request $request)
    {
        $request->validate([
            'last_name' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'civility' => ['required', 'integer', 'in:1,2'],
            'role' => ['required', 'integer'],
        ]);

        $role = Role::where('id', $request->role)->first();
        $permissions = $role->permissions()->pluck('id')->toArray();
        
        return to_route('users.create')->with('permissions', $permissions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $user = User::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'email' => $request->email,
            'password' => Hash::make('password'),
            'civility_id' => $request->civility,
            'role_id' => $request->role,
        ]);

        $user->groups()->attach($request->groups);

        $user->permissions()->attach($request->permissions);

        return to_route('users.index')->with('success', "L'utilisateur $user->first_name $user->last_name a été créé avec succès");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return Inertia::render('Users/Edit', [
            'civilities' => Civility::all(),
            'roles' => Role::all(),
            'groups' => Group::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->groups()->detach($user->groups()->pluck('id')->toArray());
        $user->permissions()->detach($user->permissions()->pluck('id')->toArray());
        $user->delete();
    }

    /**
     * Import a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        try {
            Excel::import(new UserImport, $request->file);
        } catch (Exception $error) {
            return redirect()->back()->with('error', "Erreur lors de l'import");
        }

        return redirect()->back()->with('success', 'Les utilisateurs ont été importés avec succès');
    }

    /**
     * Export a resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        return Excel::download(new UserExport, 'users.xlsx');
    }
}
