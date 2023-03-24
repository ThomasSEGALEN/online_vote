<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Role;
use App\Services\ExportImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportImportController extends Controller
{
    public function __construct(private ExportImportService $exportImportService)
    {
    }

    /**
     * Export a resource from storage.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|string
     */
    public function exportUsers(): StreamedResponse|string
    {
        $this->authorize('create', User::class);

        return fastexcel($this->exportImportService->exportUsers())->download('users.xlsx');
    }

    /**
     * Import a resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importUsers(Request $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $this->exportImportService->importUsers($request);

        return to_route('users.index')->with('success', 'Les utilisateurs ont été importés avec succès');
    }

    /**
     * Export a resource from storage.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|string
     */
    public function exportRoles(): StreamedResponse|string
    {
        $this->authorize('create', Role::class);

        return fastexcel($this->exportImportService->exportRoles())->download('roles.xlsx');
    }

    /**
     * Import a resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importRoles(Request $request): RedirectResponse
    {
        $this->authorize('create', Role::class);

        $this->exportImportService->importRoles($request);

        return to_route('roles.index')->with('success', 'Les roles ont été importés avec succès');
    }

    /**
     * Export a resource from storage.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|string
     */
    public function exportGroups(): StreamedResponse|string
    {
        $this->authorize('create', Group::class);

        return fastexcel($this->exportImportService->exportGroups())->download('groups.xlsx');
    }

    /**
     * Import a resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importGroups(Request $request): RedirectResponse
    {
        $this->authorize('create', Group::class);

        $this->exportImportService->importGroups($request);

        return to_route('groups.index')->with('success', 'Les groupes ont été importés avec succès');
    }
}
