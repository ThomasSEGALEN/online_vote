<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Role::class);

        return Inertia::render('Roles/Index', [
            'roles' =>
                Role::when($request->input('search'), fn ($query, $search) =>
                    $query->where('name', 'like', "%{$search}%")
                )
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

        return Inertia::render('Roles/Create', [
            'permissions' => Permission::all()->map(fn ($permission) => [
                'id' => $permission->id,
                'name' => $permission->name
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
        $this->authorize('create', Role::class);

        dd($request->permissions);

        $request->validate([
            'name' => ['required', 'string', 'unique:roles']
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        $role->permissions()->attach($request->permissions);
        
        // if ($request->users) {
        //     foreach ($request->users as $user) {
        //         $user->permissions()->attach($role->permissions()->pluck('id')->toArray());
        //     }
        // }

        return to_route('roles.index')->with('success', "Le rôle $role->name a été créé avec succès");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
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
            fastexcel()->import($request->file('rolesFile'), function ($row) {
                $role = Role::create([
                    'name' => $row['name'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $permissions = array_map('intval', explode('&', $row['permissions']));

                if (!in_array(0, $permissions)) $role->permissions()->attach($permissions);

                return $role;
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

        return to_route('roles.index')->with('success', 'Les roles ont été importés avec succès');
    }

    /**
     * Export a resource from storage.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|string
     */
    public function export(): StreamedResponse|string
    {
        return fastexcel(Role::select('name', 'created_at', 'updated_at')->get())->download('roles.xlsx');
    }
}
