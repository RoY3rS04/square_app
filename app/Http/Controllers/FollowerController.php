<?php

namespace App\Http\Controllers;

use App\Events\NotificationCreated;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    function store(Request $request): RedirectResponse {
        $data = $request->validate([
            'followed_id' => ['required']
        ]);

        if($following = Auth::user()->following()->where('followed_id', $request->followed_id)->first()) {
            Auth::user()->following()->detach($following);
        } else {
            Auth::user()->following()->attach($data);

            $user = User::find($request->followed_id);

            if($user) {
                Auth::user()->stopPostsFrom()->detach($user);
                Auth::user()->stopNotificationsFrom()->detach($user);
            }

            $notification = Notification::create([
                'user_id' => Auth::id(),
                'notificationable_type' => 'App\\Models\\User',
                'notificationable_id' => $request->followed_id
            ]);

            broadcast(new NotificationCreated($notification->load(['user' => ['media'],'notificationable'])))->toOthers();
        }

        return back();
    }

    function stopPostsFrom(User $user): RedirectResponse {

        Auth::user()->stopPostsFrom()->attach($user);

        return back();
    }

    function destroy(User $user): RedirectResponse {

        Auth::user()->following()->detach($user);

        return back();
    }
}
