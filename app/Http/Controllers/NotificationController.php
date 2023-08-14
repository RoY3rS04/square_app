<?php

namespace App\Http\Controllers;

use App\Events\NotificationCreated;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use function PHPUnit\Framework\isInstanceOf;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return inertia('Home/Notifications', [
            'user' => Auth::user()->load(['media','friendOf' => ['media'],'friendsOfMine' => ['media'], 'following', 'stopNotificationsFrom']),
            'notifications' => Notification::with(['notificationable' => function(MorphTo $morphTo) {
                $morphTo->morphWith([
                    Like::class => ['likeable'],
                    Comment::class => ['commentable']
                ]);
            }, 'user' => ['media']])->where(function ($builder) {

                $builder->whereNotIn('user_id', array_map(fn (User $friend) => $friend->id, [...Auth::user()->removeNotificationsFrom, Auth::user()]));
            })->get()->filter(function($notification) {
                if($notification->notificationable_type=='App\\Models\\Like') {
                    if($notification->notificationable?->likeable?->user_id == Auth::id()) {
                        return $notification;
                    } else {
                        return false;
                    }
                } else if($notification->notificationable_type == 'App\\Models\\User') {
                    if($notification->notificationable_id == Auth::id()) {
                        return $notification;
                    }  else {
                        return false;
                    }
                }
                return $notification;
            })->values()
        ]);
    }

    public function stopNotificationsFrom(User $user): RedirectResponse {

        Auth::user()->stopNotificationsFrom()->attach($user);

        return back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        dd($request);

        $data = $request->validate([
            'user_id' => ['required'],
           'notificationable_id' => ['required'],
           'notificationable_type' => ['required']
        ]);

        $notification = Notification::create($data)->load(['user','notificationable']);

        broadcast(new NotificationCreated($notification->load(['user','notificationable'])))->toOthers();

        return redirect()->refresh();
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        Auth::user()->removeNotificationsFrom()->attach($user);

        return back();
    }
}
