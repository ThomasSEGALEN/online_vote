<?php

namespace App\Services;

use App\Http\Requests\SessionPreupdateRequest;
use App\Http\Requests\SessionStoreRequest;
use App\Http\Requests\SessionUpdateRequest;
use App\Models\Document;
use App\Models\Group;
use App\Models\LabelSet;
use App\Models\Session;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteAnswer;
use App\Models\VoteType;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SessionService
{
    /**
     * List answers grouped by label data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mapLabelSets(): Collection
    {
        return LabelSet::orderBy('id')->get()->map(
            fn ($labelSet) => [
                'id' => $labelSet->id,
                'name' => $labelSet->name,
                'answers' => $labelSet->answers->map(
                    fn ($answer) => [
                        'name' => $answer->name,
                        'color' => $answer->color
                    ]
                )
            ]
        );
    }

    /**
     * List users data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mapUsers(): Collection
    {
        return User::orderBy('last_name')->orderBy('first_name')->get()->map(fn ($user) => [
            'id' => $user->id,
            'name' => $user->last_name . ' ' . $user->first_name
        ]);
    }

    /**
     * List grouped users data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mapGroupedUsers(): Collection
    {
        return Group::orderBy('name')->get()->map(
            fn ($group) => [
                'label' => $group->name,
                'options' => $group->users()->orderBy('last_name')->orderBy('first_name')->get()->map(
                    fn ($user) => [
                        'id' => $user->id,
                        'name' => $user->last_name . ' ' . $user->first_name
                    ]
                )
            ]
        );
    }

    /**
     * List statuses data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mapVoteTypes(): Collection
    {
        return VoteType::orderBy('id')->get()->map(fn ($vote_type) => [
            'id' => $vote_type->id,
            'name' => $vote_type->name
        ]);
    }

    /**
     * List votes types data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mapStatuses(): Collection
    {
        return Status::orderBy('id')->get()->map(fn ($status) => [
            'id' => $status->id,
            'name' => $status->name
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        return [
            'sessions' =>
            Session::where('title', 'like', '%' . $request->input('search') . '%')
                ->orderBy('id')
                ->paginate(20)
                ->appends($request->only('search'))
                ->through(
                    fn ($session) =>
                    [
                        'id' => $session->id,
                        'title' => $session->title,
                        'start_date' => $session->start_date,
                        'end_date' => $session->end_date,
                        'status_id' => $session->status_id
                    ]
                ),
            'labelSets' => $this->mapLabelSets(),
            'statuses' => $this->mapStatuses(),
            'filters' => $request->only('search'),
            'can' => [
                'createSessions' => $request->user()->permissions->contains('name', 'createSessions'),
                'deleteSessions' => $request->user()->permissions->contains('name', 'deleteSessions'),
                'updateSessions' => $request->user()->permissions->contains('name', 'updateSessions')
            ]
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return array
     */
    public function create(): array
    {
        return [
            'users' => $this->mapUsers(),
            'groupedUsers' => $this->mapGroupedUsers(),
            'statuses' => $this->mapStatuses(),
            'voteTypes' => $this->mapVoteTypes(),
            'labelSets' => $this->mapLabelSets(),
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\SessionStoreRequest $request
     * @return \App\Models\Session
     */
    public function store(SessionStoreRequest $request): Session
    {
        $session = Session::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status_id' => $request->status
        ]);

        if ($request->file('documents')) {
            foreach ($request->file('documents') as $file) {
                $document = Document::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => time() . '_' . $file->getClientOriginalName(),
                    'session_id' => $session->id
                ]);

                $file->move(public_path('documents'), $document->path);
            }
        }

        $session->users()->attach($request->users);

        for ($index = 0; $index < $request->amount; $index++) {
            $vote = Vote::create([
                'title' => $request->votes['title'][$index],
                'description' => $request->votes['description'][$index],
                'start_date' => $request->votes['start_date'][$index],
                'end_date' => $request->votes['end_date'][$index],
                'session_id' => $session->id,
                'status_id' => $request->votes['status'][$index],
                'type_id' => $request->votes['type'][$index]
            ]);

            $vote->users()->attach($request->votes['users'][$index]);
            $vote->labelSets()->attach($request->votes['label_sets'][$index]);

            foreach ($request->votes['answers'][$index] as $answer) {
                if (!empty($answer['name'])) {
                    VoteAnswer::create([
                        'name' => $answer['name'],
                        'color' => $answer['color']  ?? "#000000",
                        'vote_id' => $vote->id
                    ]);
                }
            }
        }

        return $session;
    }

    /**
     * Display the specified resource.
     *
     * @return array
     */
    public function show(Session $session): array
    {
        return [
            'session' => [
                'id' => $session->id,
                'title' => $session->title,
                'description' => $session->description,
                'start_date' => $session->start_date,
                'end_date' => $session->end_date,
                'status_id' => $session->status_id,
                'users' => $session->users()->pluck('id')->toArray(),
                'documents' => $session->documents->map(fn ($document) => [
                    'id' => $document->id,
                    'name' => $document->name,
                    'path' => public_path("documents/" . $document->path)
                ])
            ],
            'users' => $this->mapUsers()
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Session $session
     * @return array
     */
    public function edit(Session $session): array
    {
        return [
            'session' => [
                'id' => $session->id,
                'title' => $session->title,
                'description' => $session->description,
                'start_date' => $session->start_date,
                'end_date' => $session->end_date,
                'status_id' => $session->status_id,
                'votes' => $session->votes->map(fn ($vote) => [
                    'id' => $vote->id,
                    'title' => $vote->title,
                    'description' => $vote->description,
                    'start_date' => $vote->start_date,
                    'end_date' => $vote->end_date,
                    'status_id' => $vote->status_id,
                    'type_id' => $vote->type_id,
                    'answers' => $vote->answers->map(fn ($answer) => [
                        'name' => $answer->name,
                        'color' => $answer->color
                    ]),
                    'label_sets' => $vote->labelSets->map(fn ($label_set) => [
                        'id' => $label_set->id,
                        'name' => $label_set->name
                    ]),
                    'users' => $vote->users()->pluck('id')->toArray()
                ]),
                'users' => $session->users()->pluck('id')->toArray()
            ],
            'users' => $this->mapGroupedUsers(),
            'statuses' => $this->mapStatuses(),
            'voteTypes' => $this->mapVoteTypes(),
            'labelSets' => $this->mapLabelSets(),
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\SessionUpdateRequest $request
     * @param \App\Models\Session $session
     */
    public function preupdate(SessionPreupdateRequest $request, Session $session)
    {
        if (($request->title !== $session->title) && Session::where('title', $request->title)->first()) {
            $request->validate(['title' => ['required', 'string', 'unique:sessions']]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\SessionUpdateRequest $request
     * @param \App\Models\Session $session
     * @return \App\Models\Session
     */
    public function update(SessionUpdateRequest $request, Session $session): Session
    {
        if (($request->title !== $session->title) && Session::where('title', $request->title)->first()) {
            $request->validate(['title' => ['required', 'string', 'unique:sessions']]);
        }

        $session->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status_id' => $request->status
        ]);

        foreach ($session->documents as $document) {
            unlink(public_path('documents') . "\\" . $document->path);

            $document->delete();
        }

        if ($request->file('documents')) {
            foreach ($request->file('documents') as $file) {
                $document = Document::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => time() . '_' . $file->getClientOriginalName(),
                    'session_id' => $session->id
                ]);

                $file->move(public_path('documents'), $document->path);
            }
        }

        $session->users()->sync($request->users);

        foreach ($request->session->votes as $key => $vote) {
            $vote->update([
                'title' => $request->votes['title'][$key],
                'description' => $request->votes['description'][$key],
                'start_date' => $request->votes['start_date'][$key],
                'end_date' => $request->votes['end_date'][$key],
                'status_id' => $request->votes['status'][$key],
                'type_id' => $request->votes['type'][$key]
            ]);

            $vote->users()->sync($request->votes['users'][$key]);
            $vote->labelSets()->sync($request->votes['label_sets'][$key]);
            VoteAnswer::where('vote_id', $vote->id)->delete();

            foreach ($request->votes['answers'][$key] as $answer) {
                if (!empty($answer['name'])) {
                    VoteAnswer::create([
                        'name' => $answer['name'],
                        'color' => $answer['color']  ?? "#000000",
                        'vote_id' => $vote->id
                    ]);
                }
            }
        }

        return $session;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Session $session
     */
    public function destroy(Session $session)
    {
        $session->delete();
    }
}
