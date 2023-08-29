<?php

namespace App\Services;

use App\Models\Session;
use App\Models\Status;
use App\Models\VoteAnswer;
use App\Models\VoteResult;
use App\Models\VoteType;
use Illuminate\Http\Request;

class VoteService
{
    /**
     * Display home page with a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function home(Request $request): array
    {
        return [
            'sessions' =>
            Session::when(
                $request->input('status'),
                fn ($query, $status) => $query->where('status_id', $status)
            )
                ->where('title', 'like', '%' . $request->input('search') . '%')
                ->whereHas(
                    'users',
                    fn ($query) => !$request->user()->permissions->contains('name', 'viewAnySessions')
                        ? $query->where('users.id', $request->user()->id)
                        : null
                )
                ->orderBy('status_id')
                ->orderBy('id', 'desc')
                ->paginate(10)
                ->appends($request->only(['status', 'search']))
                ->through(
                    fn ($session) =>
                    [
                        'id' => $session->id,
                        'title' => $session->title,
                        'description' => $session->description,
                        'start_date' => $session->start_date,
                        'end_date' => $session->end_date,
                        'status_id' => $session->status_id,
                        'allowed' => !$session->users->filter(fn ($user) => $user->id === auth()->user()->id)->isEmpty()
                    ]
                ),
            'statuses' => Status::orderBy('id')->get()->map(fn ($status) => [
                'id' => $status->id,
                'name' => $status->name
            ]),
            'filters' => [
                $request->only('status'),
                $request->only('search')
            ],
            'can' => [
                'createSessions' => $request->user()->permissions->contains('name', 'createSessions'),
                'deleteSessions' => $request->user()->permissions->contains('name', 'deleteSessions'),
                'updateSessions' => $request->user()->permissions->contains('name', 'updateSessions'),
            ]
        ];
    }

    /**
     * Vote to a specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\VoteAnswer
     */
    public function store(Request $request): VoteAnswer
    {
        if (VoteResult::where('user_id', $request->user()->id)->first()) {
            VoteResult::where('user_id', $request->user()->id)->delete();
        }

        VoteResult::create([
            'answer_id' => $request->answer,
            'user_id' => $request->user()->id,
            'vote_id' => $request->vote
        ]);


        $answer = VoteAnswer::where('id', $request->answer)->first();

        return $answer;
    }
}
