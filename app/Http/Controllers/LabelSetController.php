<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelSetStoreRequest;
use App\Models\Answer;
use App\Models\LabelSet;

class LabelSetController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(LabelSetStoreRequest $request)
    {
        $this->authorize('create', LabelSet::class);

        $labelSet = LabelSet::create([
            'name' => $request->label
        ]);

        for ($index = 0; $index < $request->amount; $index++) {
            Answer::create([
                'name' => $request->names[$index],
                'color' => $request->colors[$index] ?? "#000000",
                'label_set_id' => $labelSet->id
            ]);
        }

        return to_route('sessions.index')->with('success', "Le jeu d'étiquettes $request->label a été créé avec succès");
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
