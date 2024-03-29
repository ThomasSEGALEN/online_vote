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
use App\Models\VoteResult;
use App\Models\VoteType;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

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
            fn ($label_set) => [
                'id' => $label_set->id,
                'name' => $label_set->name,
                'answers' => $label_set->answers->map(
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

                Storage::disk('public')->put('documents/' . $document->path, file_get_contents($file));
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

            foreach ($request->votes['answers'][$index] as $answer) {
                if (!empty($answer['name'])) {
                    VoteAnswer::create([
                        'name' => $answer['name'],
                        'color' => $answer['color']  ?? "#000000",
                        'vote_id' => $vote->id
                    ]);
                }
            }

            if (!empty($request->votes['label_sets'][$index])) {
                foreach ($request->votes['label_sets'][$index] as $vote_label_set) {
                    $label_set = LabelSet::where('id', $vote_label_set)->first();

                    foreach ($label_set->answers as $answer) {
                        VoteAnswer::create([
                            'name' => $answer['name'],
                            'color' => $answer['color']  ?? "#000000",
                            'vote_id' => $vote->id,
                            'label_set_id' => $answer['id']
                        ]);
                    }
                }
            };
        }

        return $session;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Session $session
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
                ]),
                'votes' => $session->votes()->paginate(1)->through(
                    fn ($vote) =>
                    [
                        'id' => $vote->id,
                        'title' => $vote->title,
                        'description' => $vote->description,
                        'start_date' => $vote->start_date,
                        'end_date' => $vote->end_date,
                        'status_id' => $vote->status_id,
                        'type_id' => $vote->type_id,
                        'users' => $vote->users->map(fn ($user) => [
                            'id' => $user->id,
                            'name' => $user->last_name . ' ' . $user->first_name
                        ]),
                        'answers' => $vote->answers->map(fn ($answer) => [
                            'id' => $answer->id,
                            'name' => $answer->name,
                            'color' => $answer->color,
                            'count' => VoteResult::selectRaw('COUNT(*) as count')->where('answer_id', $answer->id)->first()->count,
                            'users' =>
                            VoteResult::select('vote_results.user_id', 'users.first_name', 'users.last_name')
                                ->join('users', 'vote_results.user_id', '=', 'users.id')
                                ->where('answer_id', $answer->id)
                                ->get()
                                ->map(
                                    fn ($result) =>
                                    [
                                        'id' => $result->user_id,
                                        'name' => $result->first_name . ' ' . $result->last_name,
                                    ]
                                )
                        ]),
                        'allowed' => !$vote->users->filter(fn ($user) => $user->id === auth()->user()->id)->isEmpty()
                    ]
                ),
            ],
            'users' => $this->mapUsers(),
            'can' => [
                'createSessions' => auth()->user()->permissions->contains('name', 'createSessions'),
                'deleteSessions' => auth()->user()->permissions->contains('name', 'deleteSessions'),
                'updateSessions' => auth()->user()->permissions->contains('name', 'updateSessions'),
                'viewSessions' => auth()->user()->permissions->contains('name', 'viewSessions')
            ]
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

                Storage::disk('public')->put('documents/' . $document->path, file_get_contents($file));
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

            $answers = [];

            foreach ($request->votes['answers'][$key] as $answer) {
                if (isset($answer['name'])) {
                    array_push($answers, $answer['name']);

                    $vote_answer = VoteAnswer::where('vote_id', $vote->id)->where('name', $answer['name']);

                    if (!$vote_answer->first()) {
                        $vote_answer->delete();

                        VoteAnswer::create([
                            'name' => $answer['name'],
                            'color' => $answer['color']  ?? "#000000",
                            'vote_id' => $vote->id
                        ]);
                    }
                }
            }

            VoteAnswer::where('vote_id', $vote->id)->whereNotIn('name', $answers)->delete();

            if (!empty($request->votes['label_sets'][$key])) {
                foreach ($request->votes['label_sets'][$key] as $vote_label_set) {
                    $label_set = LabelSet::where('id', $vote_label_set)->first();

                    foreach ($label_set->answers as $answer) {
                        VoteAnswer::create([
                            'name' => $answer['name'],
                            'color' => $answer['color']  ?? "#000000",
                            'vote_id' => $vote->id,
                            'label_set_id' => $answer['id']
                        ]);
                    }
                }
            }
        }

        return $session;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Session $session
     * @return void
     */
    public function destroy(Session $session): void
    {
        $session->delete();
    }
}
