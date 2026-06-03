<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\NotImplementedException;

class UserController extends Controller
{
    // Responses should be standardised, but theres a bit more before that (mainly modifying the login route?)
    // should also make some basic middleware that only allows modifications if the user is actually the user.
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        // Currently won't be used? I don't think we'll have a user list. Maybe in an admin panel or smtn
        throw new NotImplementedException();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        throw new NotImplementedException();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $request = $request->validated();

        $user = User::create($request);

        return $user;
    }

    public function authenticate(AuthenticateUserRequest $request)
    {
        $request = $request->validated();

        // should return this as errorbag?
        if (!Auth::attempt($request)) {
            return response()->json([
                'message' => 'invalid credentials',
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->tokens()->delete();

        $token = $user->createToken('pat')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if ($user->visibility == true) {
            return $user;
        }
        else {
            return response()->json('Private Profile');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        throw new NotImplementedException();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $request = $request->validated();
        
        $user = User::find($id);
        $user->update($request);

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->id != $id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::find($id);
        $user->delete();

        return response()->json('success');
    }
}
