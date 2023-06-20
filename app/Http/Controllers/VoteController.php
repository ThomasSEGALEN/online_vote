<?php

namespace App\Http\Controllers;

use App\Models\VoteAnswer;
use App\Models\VoteResult;
use App\Services\VoteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class VoteController extends Controller
{
    public function __construct(private VoteService $voteService)
    {
    }

    /**
     * Display home page with a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function home(Request $request): Response
    {
        return inertia('Home', $this->voteService->home($request));
    }

    /**
     * Vote to a specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function vote(Request $request): RedirectResponse
    {
        $answer = $this->voteService->vote($request);

        return redirect()->back()->with('success', "Vous avez voté pour $answer->name");
    }
}
