<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class ExportImportService
{
    /**
     * Export a resource from storage.
     *
     * @return \Illuminate\Support\Collection
     */
    public function exportUsers(): Collection
    {
        return User::select('last_name', 'first_name', 'email', 'civility_id', 'role_id', 'created_at', 'updated_at')->get();
    }

    /**
     * Import a resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User|\Illuminate\Http\RedirectResponse
     */
    public function importUsers(Request $request): User|RedirectResponse
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
                    return back()->with('error', "Erreur lors de l'import : fichier invalide");
                    break;
                case '23000':
                    return back()->with('error', "Erreur lors de l'import : champ duppliqué");
                    break;
                default:
                    return back()->with('error', "Erreur lors de l'import");
                    break;
            }
        }
    }

    /**
     * Export a resource from storage.
     *
     * @return \Illuminate\Support\Collection
     */
    public function exportRoles(): Collection
    {
        return Role::select('name', 'created_at', 'updated_at')->get();
    }

    /**
     * Import a resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Role|\Illuminate\Http\RedirectResponse
     */
    public function importRoles(Request $request): Role|RedirectResponse
    {
        try {
            fastexcel()->import($request->file('rolesFile'), function ($row) {
                $role = Role::create([
                    'name' => $row['name'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $permissions = array_map('intval', explode('&', $row['permissions']));

                if (!in_array(0, $permissions)) {
                    $role->permissions()->attach($permissions);
                }

                return $role;
            });
        } catch (Exception $exception) {
            switch ($exception->getCode()) {
                case '0':
                    return back()->with('error', "Erreur lors de l'import : fichier invalide");
                    break;
                case '23000':
                    return back()->with('error', "Erreur lors de l'import : champ duppliqué");
                    break;
                default:
                    return back()->with('error', "Erreur lors de l'import");
                    break;
            }
        }
    }

    /**
     * Export a resource from storage.
     *
     * @return \Illuminate\Support\Collection
     */
    public function exportGroups(): Collection
    {
        return Group::select('name', 'created_at', 'updated_at')->get();
    }

    /**
     * Import a resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Role|\Illuminate\Http\RedirectResponse
     */
    public function importGroups(Request $request): Group|RedirectResponse
    {
        try {
            fastexcel()->import($request->file('groupsFile'), function ($row) {
                $group = Group::create([
                    'name' => $row['name'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $users = array_map('intval', explode('&', $row['users']));

                if (!in_array(0, $users)) {
                    $group->users()->attach($users);
                }

                return $group;
            });
        } catch (Exception $exception) {
            switch ($exception->getCode()) {
                case '0':
                    return back()->with('error', "Erreur lors de l'import : fichier invalide");
                    break;
                case '23000':
                    return back()->with('error', "Erreur lors de l'import : champ duppliqué");
                    break;
                default:
                    return back()->with('error', "Erreur lors de l'import");
                    break;
            }
        }
    }
}
