<?php

namespace App\Http\Controllers;

use App\Events\SavedCreated;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;

class SavedController extends Controller
{

    public function store(Request $request): RedirectResponse {

        $data = $request->validate([
           'user_id' => ['required'],
           'saveable_id' => ['required'],
           'saveable_type' => ['required', 'string']
        ]);

        $post = Post::find($request->saveable_id);

        if($saved = $post->saveds()->where('user_id', $request->user_id)->first()) {
            $saved->delete();
            $wasSaved = false;
        } else {
            $saved = $post->saveds()->create($data);
            $wasSaved = true;
        }

        broadcast(new SavedCreated([
            'saved' => $saved->load(['saveable']),
            'wasSaved' => $wasSaved
        ]));

        return back();
    }
}
