<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use App\Models\Vote;
use App\Services\ExportImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportImportController extends Controller
{
    public function __construct(private ExportImportService $exportImportService)
    {
    }

    /**
     * Return message from exception code.
     *
     * @param Exception $exception
     * @return string
     */
    public function getExceptionMessage($exception): string
    {
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

        $exception = $this->exportImportService->importUsers($request);

        if ($exception) {
            $this->getExceptionMessage($exception);
        }

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

        $exception = $this->exportImportService->importRoles($request);

        if ($exception) {
            $this->getExceptionMessage($exception);
        }

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

        $exception = $this->exportImportService->importGroups($request);

        if ($exception) {
            $this->getExceptionMessage($exception);
        }

        return to_route('groups.index')->with('success', 'Les groupes ont été importés avec succès');
    }

    /**
     * Export a resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function exportVotes(Request $request): Response
    {
        $title = Vote::select('title')->where('id', $request->route('vote'))->first()->title;

        return ($this->exportImportService->exportVotes($request))->download($title . '.pdf');
    }
}
