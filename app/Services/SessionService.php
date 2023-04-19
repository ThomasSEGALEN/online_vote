<?php

namespace App\Services;

use App\Http\Requests\SessionStoreRequest;
use App\Http\Requests\SessionUpdateRequest;
use App\Models\Document;
use App\Models\Group;
use App\Models\Session;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SessionService
{
    /**
     * List users data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mapUsers(): Collection
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
     * List status data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mapStatus()
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
            Session::when(
                $request->input('search'),
                fn ($query, $search) => $query->where('title', 'like', '%' . $search . '%')
            )
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
            'statuses' => $this->mapStatus(),
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
            'statuses' => $this->mapStatus()
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

        return $session;
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
                'users' => $session->users()->pluck('id')->toArray()
            ],
            'users' => $this->mapUsers(),
            'statuses' => $this->mapStatus()
        ];
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

        return $session;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Session $session
     */
    public function destroy(Session $session)
    {
        $session->users()->detach($session->users()->pluck('id')->toArray());
        $session->delete();
    }
}
