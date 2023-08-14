<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class RoutesController extends Controller
{
    function friends(): Response {
        return inertia('Home/Friends', [
            'user' => Auth::user()->load(['media','likes', 'friendOf' => ['media'],'friendsOfMine' => ['media'],'following', 'stopNotificationsFrom']),
        ]);
    }

    function photos(): Response {
        return inertia('Home/Photos', [
            'user' => Auth::user()->load(
                [
                    'media',
                    'likes',
                    'posts' => ['media'],
                    'friendOf' => ['media'],
                    'friendsOfMine' => ['media'],
                    'following',
                    'stopNotificationsFrom'
                ]
            ),
        ]);
    }

    function profile(): Response {
        return inertia('Home/Profile', [
            'user' => Auth::user()->load(['posts' => ['likes','comments','media','user', 'saveds'], 'media', 'likes','friendOf' => ['media'],'friendsOfMine' => ['media'], 'following', 'followers', 'saveds', 'stopNotificationsFrom']),
        ]);
    }

    function notifications(): Response {
        return inertia('Home/Notifications', [
            'user' => Auth::user()->load(['media','likes','friendOf' => ['media'],'friendsOfMine' => ['media'], 'following']),
            'notifications' => Notification::all(),
        ]);
    }

    function people(): Response
    {
        return inertia('Home/People', [
            'user' => Auth::user()->load(['media','likes','friendOf' => ['media'],'friendsOfMine' => ['media'], 'following', 'stopNotificationsFrom']),
            'users' => User::query()->with(['media'])
                ->where(function($builder) {
                    $friends = [
                        ...Auth::user()->friendOf,
                        ...Auth::user()->friendsOfMine
                    ];

                    $builder->where('id','!=',Auth::id())
                            ->whereNotIn('id', array_map(fn (User $friend) => $friend->id, $friends));
                })->get(),
        ]);
    }
}
