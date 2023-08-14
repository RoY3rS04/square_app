<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Response;

class UserController extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'workplace' => ['nullable', 'string', 'max:255'],
            'relation' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'string', 'max:255']
        ]);

        $realData = array_filter($data, fn($field) => $field != null);

        $user->update($realData);

        if(isset($request->allFiles()['user_image'])) {
            $user->addMedia($request->allFiles()['user_image']->path())
                ->preservingOriginal()
                ->toMediaCollection('user_images');
        }

        if(isset($request->allFiles()['user_background'])) {
            $user->addMedia($request->allFiles()['user_background']->path())
                ->preservingOriginal()
                ->toMediaCollection('user_backgrounds');
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if(Hash::check($request->password, $user->getAuthPassword())) {

            $posts = Post::query()->where('user_id', $user->id)->get();

            foreach ($posts as $post) {
                $post->comments()->delete();
                $post->likes()->delete();
            }

            $notifications = Notification::query()->where('user_id',$user->id)->get();

            foreach ($notifications as $notification) {
                $notification->delete();
            }

            $user->delete();
            return redirect('/');
        } else {
            return back();
        }
    }
}
