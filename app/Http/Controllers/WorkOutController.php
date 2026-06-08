<?php

namespace App\Http\Controllers;

use App\Achievements\TrackFullWorkOutLength;
use App\Achievements\TrackRuns;
use App\Achievements\TrackSprints;
use App\Achievements\TrackWalks;
use App\Achievements\TrackWorkOutLength;
use App\Achievements\TrackWorkOuts;
use App\Http\Requests\StoreWorkOutRequest;
use App\Models\Leaderboard;
use App\Models\WorkOut;
use App\Services\DistanceService;
use Illuminate\Support\Facades\Auth;

class WorkOutController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkOutRequest $request)
    {
        $request = $request->validated();
        $user_id = Auth::user()->id;

        $result = DistanceService::calculateDistance($request['waypoints']);
        $finalResult = $result[count($result) - 1];

        $workOut = new WorkOut();
        $workOut->user_id = $user_id;
        $workOut->length = $finalResult->m;
        $workOut->speed = $finalResult->speed;
        $workOut->type = $finalResult->type;
        $workOut->points = $finalResult->points;
        $workOut->save();

        // Achievements
        $user = Auth::find($user_id);
        $user->addProgress(new TrackWorkOuts(), 1);
        $user->setProgress(new TrackWorkOutLength(), $workOut->length);
        $user->addProgress(new TrackFullWorkOutLength(), $workOut->length);
        if ($workOut->type == 'walking') $user->addProgress(new TrackWalks(), 1);
        if ($workOut->type == 'running') $user->addProgress(new TrackRuns(), 1);
        if ($workOut->type == 'sprinting') $user->addProgress(new TrackSprints(), 1);

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
