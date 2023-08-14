<?php

namespace App\Http\Controllers;

use App\Events\NotificationCreated;
use App\Events\PostCreated;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request) :RedirectResponse
    {
        $data = $request->validate([
            'description' => ['required', 'min:5', 'string'],
            'user_id' => [Rule::exists('users', 'id')]
        ]);

        $post = Post::create($data);

        if(isset($request->allFiles()['post_images'])) {
            foreach ($request->allFiles()['post_images'] as $image) {
                $post->addMedia($image->path())
                    ->preservingOriginal()
                    ->toMediaCollection('post_images'.$post->id);
            }
        }

        $notification_data = [
            'user_id' => $request->user_id,
            'notificationable_id' => $post->id,
            'notificationable_type' => 'App\\Models\\Post'
        ];

        $notification = Notification::create($notification_data);

        broadcast(new NotificationCreated($notification->load(['user' => ['media'],'notificationable'])))->toOthers();

        broadcast(new PostCreated($post->load(['media','likes','comments','saveds' ,'user' => ['media']])))->toOthers();

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): JsonResponse
    {
        //$post = Post::find($id);

        return response()->json($post->load(['comments' => ['user' => ['media']]]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): JsonResponse
    {

        return response()->json($post->load('media'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        $request->validate([
            'description' => ['required','string'],
            'deletedImages' => ['nullable', 'array']
        ]);

        if ($request->deletedImages) {
            $post->media()->whereIn('id',$request->deletedImages)->delete();
        }

        if(isset($request->allFiles()['newImages'])) {
            foreach ($request->allFiles()['newImages'] as $image) {
                $post->addMedia($image->path())
                    ->preservingOriginal()
                    ->toMediaCollection('post_images'.$post->id);
            }
        }

        $post->update(['description' => $request->description]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return back();
    }
}
