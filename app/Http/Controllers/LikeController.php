<?php

namespace App\Http\Controllers;

use App\Events\LikeEvent;
use App\Events\NotificationCreated;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    function store(Request $request): RedirectResponse {
        $data = $request->validate([
           'likeable_id' => ['required'],
           'likeable_type' => ['required']
        ]);

        switch ($request->likeable_type) {
            case Post::class : {

                $post = Post::find($request->likeable_id);

                $likes = $post->likes->count();
                $wasCreated = null;

                if ($like = $post->likes()->where('user_id', Auth::id())->first()) {
                    $like->delete();
                    $likes--;
                    $wasCreated = false;
                } else {
                    $like = $post->likes()->create([
                        'user_id' => Auth::id(),
                        ...$data
                    ]);
                    $wasCreated = true;
                    $likes++;

                    $notification_data = [
                        'user_id' => Auth::id(),
                        'notificationable_id' => $like->id,
                        'notificationable_type' => 'App\\Models\\Like'
                    ];

                    $notification = Notification::create($notification_data);

                    broadcast(new NotificationCreated($notification->load(
                        [
                            'user' => ['media'],
                            'notificationable' => ['likeable']
                        ]
                    )))->toOthers();
                }

                broadcast(new LikeEvent([
                    'like' => $like->load(['likeable' => ['user']]),
                    'likes' => $likes,
                    'wasCreated' => $wasCreated
                ]))->toOthers();

                break;
            }
        }

        return back();
    }
}
