<?php

namespace App\Http\Controllers;

use App\Http\Requests\SessionPrestoreRequest;
use App\Http\Requests\SessionPreupdateRequest;
use App\Http\Requests\SessionStoreRequest;
use App\Http\Requests\SessionUpdateRequest;
use App\Models\Session;
use App\Services\SessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class SessionController extends Controller
{
    public function __construct(private SessionService $sessionService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Session::class);

        return inertia('Sessions/Index', $this->sessionService->index($request));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(): Response
    {
        $this->authorize('create', Session::class);

        return inertia('Sessions/Create', $this->sessionService->create());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\SessionPrestoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function prestore(SessionPrestoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Session::class);

        return to_route('sessions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\SessionStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SessionStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Session::class);

        $session = $this->sessionService->store($request);

        $success = '';

        if ($request->amount > 1) {
            $success = "La séance $session->title ($request->amount votes) a été créée avec succès";
        } else {
            $success = "La séance $session->title ($request->amount vote) a été créée avec succès";
        }

        return to_route('sessions.index')->with('success', $success);
    }

    /**
     * Display the specified resource.
     * 
     * @param \App\Models\Session $session
     * @return \Inertia\Response
     */
    public function show(Session $session): Response
    {
        $this->authorize('view', $session);

        return inertia('Sessions/Show', $this->sessionService->show($session));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Session $session
     * @return \Inertia\Response
     */
    public function edit(Session $session): Response
    {
        $this->authorize('update', $session);

        return inertia('Sessions/Edit', $this->sessionService->edit($session));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\SessionPreupdateRequest $request
     * @param \App\Models\Session $session
     * @return \Illuminate\Http\RedirectResponse
     */
    public function preupdate(SessionPreupdateRequest $request, Session $session): RedirectResponse
    {
        $this->authorize('update', $session);

        $this->sessionService->preupdate($request, $session);

        return to_route('sessions.edit', $session);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\SessionUpdateRequest $request
     * @param \App\Models\Session $session
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SessionUpdateRequest $request, Session $session): RedirectResponse
    {
        $this->authorize('update', $session);

        $session = $this->sessionService->update($request, $session);

        return to_route('sessions.index')->with('success', "La séance $session->title a été modifiée avec succès");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Session $session
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Session $session): RedirectResponse
    {
        $this->authorize('delete', $session);

        $this->sessionService->destroy($session);

        return to_route('sessions.index')->with('success', "La séance $session->title a été supprimée avec succès");
    }
}
