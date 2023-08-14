<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    function store(Request $request): RedirectResponse {

        $data = $request->validate([
            'friend_id' => ['required']
        ]);

        Auth::user()->friendsOfMine()->attach($data);

        if(!Auth::user()->following()->where('followed_id', $request->friend_id)->first()) {
            Auth::user()->following()->attach($data);
        }

        return redirect()->refresh();
    }
}
