<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelSetStoreRequest;
use App\Models\LabelSet;
use App\Services\LabelSetService;
use Illuminate\Http\RedirectResponse;

class LabelSetController extends Controller
{
    public function __construct(private LabelSetService $labelSetService)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\LabelSetStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LabelSetStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', LabelSet::class);

        $labelSet = $this->labelSetService->store($request);

        return to_route('sessions.index')->with('success', "Le jeu d'étiquettes $labelSet->name a été créé avec succès");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\LabelSet $labelSet
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(LabelSet $labelSet): RedirectResponse
    {
        $this->authorize('delete', $labelSet);

        $labelSet->delete();

        return to_route('sessions.index')->with('success', "Le jeu d'étiquettes $labelSet->name a été supprimé avec succès");
    }
}
