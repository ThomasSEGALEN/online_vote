<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use App\Models\Vote;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
     * @return bool|Exception
     */
    public function importUsers(Request $request): bool|Exception
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
            });
        } catch (Exception $exception) {
            return $exception;
        }

        return false;
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
     * @return bool|Exception
     */
    public function importRoles(Request $request): bool|Exception
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
            });
        } catch (Exception $exception) {
            return $exception;
        }

        return false;
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
     * @return bool|Exception
     */
    public function importGroups(Request $request): bool|Exception
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
            });
        } catch (Exception $exception) {
            return $exception;
        }

        return false;
    }

    /**
     * Export a resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Barryvdh\DomPDF\PDF
     */
    public function exportVotes(Request $request): \Barryvdh\DomPDF\PDF
    {
        $voteId = $request->route('vote');
        $base64Image = $request->query('url');
        $image = base64_decode(substr($base64Image, strpos($base64Image, ',') + 1));

        Storage::disk('public')->put('charts/vote_' . $voteId . '.png', $image);

        $vote =
            Vote::select('title', 'description')
            ->where('id', $voteId)
            ->first();

        return Pdf::loadView('vote_pdf', [
            'id' => $voteId,
            'title' => $vote->title,
            'description' => $vote->description
        ]);
    }
}
