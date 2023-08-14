<?php

namespace App\Http\Controllers;

use App\Events\CommentCreated;
use App\Events\NotificationCreated;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    //
    public function store(Request $request) {

        $data = $request->validate([
            'user_id' => ['required'],
            'comment' => ['required', 'string'],
            'commentable_id' => ['required'],
            'commentable_type' => ['required', 'string']
        ]);

        switch ($request->commentable_type) {
            case Post::class : {
                $post = Post::find($request->commentable_id);
                $comment = $post->comments()->create($data);

                $notification_data = [
                    'user_id' => $request->user_id,
                    'notificationable_id' => $comment->id,
                    'notificationable_type' => 'App\\Models\\Comment'
                ];

                $notification = Notification::create($notification_data);

                broadcast(new CommentCreated($comment->load(['user' => ['media']])));

                broadcast(new NotificationCreated($notification->load(
                    [
                        'user' => ['media'],
                        'notificationable' => ['commentable']
                    ]
                )))->toOthers();

                break;
            }
        }

    }
}
