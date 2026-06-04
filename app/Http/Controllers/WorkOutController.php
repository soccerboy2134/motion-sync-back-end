<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkOutRequest;
use App\Models\Leaderboard;
use App\Models\WorkOut;
use Illuminate\Support\Facades\Auth;

class WorkOutController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkOutRequest $request)
    {
        $request = $request->validated();

        $workOut = WorkOut::create($request);

        return $workOut;
    }

    /**
     * Display the specified resource if the user owns the resource.
     */
    public function show(string $id) {
        $workOut = WorkOut::find($id);
        $user = Auth::user();

        if ($workOut != null && $workOut->user_id == $user->id || $user->role == 'admin') {
            return $workOut;
        }
        
        return response()->json([
            'message' => 'You are not authorized to view this workout.',
        ], 403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {   
        $workOut = WorkOut::find($id);
        
        if (Auth::user()->id != $workOut->user_id) {
            return response()->json([
                'message' => 'You are not authorized to delete this workout.',
            ], 403);
        }

        $workOut->delete();

        return response()->json([
            'message' =>'Your workout was successfully deleted.'
        ], 200);
    }
}
