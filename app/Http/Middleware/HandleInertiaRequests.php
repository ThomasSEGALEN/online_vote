<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'data' => [
                'permissions' => fn () => $request->session()->get('permissions'),
            ],
            'auth' => [
                'user' => fn () => $request->user() ? $request->user()->only('last_name', 'first_name', 'email') : null,
                'civility' => $request->user() ? $request->user()->civility->only('id', 'label', 'name') : null,
                'role' => $request->user() ? $request->user()->role->only('id', 'name') : null,
                'groups' => $request->user() ? $request->user()->groups->transform(fn ($group) => $group->only('id', 'name')) : null,
                'permissions' => $request->user() ? $request->user()->permissions->transform(fn ($permission) => $permission->only('id', 'name')) : null
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }
}
