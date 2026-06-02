<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() 
    {
        
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
    public function store(StoreUserRequest $request)
    {
        $request = $request->validated();

        $user = User::create($request);

        return $user;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

        return "success :c";
    }
}
