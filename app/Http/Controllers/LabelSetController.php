<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelSetStoreRequest;
use App\Models\LabelSet;
use App\Services\LabelSetService;

class LabelSetController extends Controller
{
    public function __construct(private LabelSetService $labelSetService)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LabelSetStoreRequest $request)
    {
        $this->authorize('create', LabelSet::class);

        $labelSet = $this->labelSetService->store($request);

        return to_route('sessions.index')->with('success', "Le jeu d'étiquettes $labelSet->name a été créé avec succès");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LabelSet $labelSet)
    {
        $this->authorize('delete', $labelSet);

        $labelSet->delete();

        return to_route('sessions.index')->with('success', "Le jeu d'étiquettes $labelSet->name a été supprimé avec succès");
    }
}
