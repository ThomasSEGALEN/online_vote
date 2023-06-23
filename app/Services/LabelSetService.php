<?php

namespace App\Services;

use App\Http\Requests\LabelSetStoreRequest;
use App\Models\Answer;
use App\Models\LabelSet;

class LabelSetService
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\LabelSetStoreRequest $request
     * @return \App\Models\LabelSet
     */
    public function store(LabelSetStoreRequest $request): LabelSet
    {
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

        return $labelSet;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\LabelSet $labelSet
     * @return void
     */
    public function destroy(LabelSet $labelSet)
    {
        $labelSet->delete();
    }
}
