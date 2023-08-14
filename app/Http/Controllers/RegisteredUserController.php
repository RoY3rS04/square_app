<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() :Response
    {
        return  inertia('Home/Index', [
            'user' => Auth::user()->load(['media','likes','friendOf','friendsOfMine', 'following', 'stopNotificationsFrom']),
            'posts' => Post::with(['user' => ['media'], 'likes', 'comments', 'saveds' ,'media'])->where(function ($builder) {
                    $friends = [
                        ...Auth::user()->friendOf,
                        ...Auth::user()->friendsOfMine,
                        ...Auth::user()->following
                    ];

                    $builder->WhereIn('user_id', array_map(fn (User $friend) => $friend->id, $friends))->whereNotIn('user_id', array_map(fn (User $user) => $user->id, [...Auth::user()->stopPostsFrom]));
                })
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Auth/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'name' => ['required', 'string', 'min:5', 'max:255'],
            'password' => ['required', Rules\Password::defaults()]
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->addMediaFromDisk('default_user_image.png', 'public')
            ->preservingOriginal()
            ->toMediaCollection('user_images');

        $user->addMediaFromDisk('default_profile_background.png', 'public')
            ->preservingOriginal()
            ->toMediaCollection('user_backgrounds');

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
