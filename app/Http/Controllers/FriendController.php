<?php

namespace App\Http\Controllers;

use App\Achievements\TrackFriends;
use App\Http\Requests\UpdateFriendRequest;
use App\Models\achievements\AchievementProgress;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FriendController extends Controller
{
    /**
     * Display a listing of all resources with a relationship to the user.
     */
    public function index() {
        $user = Auth::user();

        $friendships = Friend::query()
            ->where('sender', $user->id)
            ->orWhere('receiver', $user->id)
            ->get()
            ->groupBy('status');

        return $friendships;
    }

    public function find(string $id) {
        $userId = Auth::id();
        $possibleFriends = Friend::where(function ($query) use ($id, $userId) {
            $query->where('sender', $id)
                ->where('receiver', $userId)
                ->orWhere('receiver', $id)
                ->where('sender', $userId);
        })->get();

        return $possibleFriends;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $id) {
        // Validate there is no record between the 2 users
        $sender = Auth::user();
        $receiver = User::find($id);

        // Validate the receiver's existance.
        if ($receiver === null) {
            return response()->json([
                'message' => 'The selected user does not exist.',
            ], 404);
        }

        // Validate the user is not sending a request to themselves..
        if ($sender->id === $receiver->id) {
            return response()->json([
                'message' => 'what did you think would happen..?.',
            ], 404);
        }

        if (count(Friend::hasRecords($receiver->id)) !== 0) {
            return response()->json([
                'message' => 'You already have a relationship with this user.',
                'records' => Friend::hasRecords($receiver->id),
            ], 400);
        }
        
        // Actually create the record..
        $friend = new Friend();
        $friend->sender = $sender->id;
        $friend->receiver = $receiver->id;
        $friend->status = 'pending';
        $friend->save();

        return $friend;
    }

    /**
     * Accepted Statusses: 
     * accepted
     * denied
     * unfriend
     * block
     * unblock
     */
    public function update(UpdateFriendRequest $request)
    {
        $data = $request->validated();

        $foreignUserId = $data['user_id'];
        $action = $data['status'];

        $currentUserId = Auth::id();

        $relationships = Friend::hasRecords($foreignUserId);

        if ($relationships->count() !== 1 && $action !== 'block') {
            return response()->json([
                'message' => "You don't have any pending requests with this user.", //There is technically a case where (if bugged) there are 2 friend requests. In that case, the user created this problem and they can deal with the consequences <3
            ], 404);
        }

        $relationship = $relationships->first();

        switch ($action) {

            case 'accepted':

                if ($relationship->status !== 'pending') {
                    break;
                }

                if ($relationship->receiver !== $currentUserId) {
                    return response()->json([
                        'message' => 'You cannot accept your own friend request.',
                    ], 403);
                }

                $relationship->status = 'friend';
                $relationship->save();

                // Achievement
                AchievementProgress::progressChain('friends', 1);

                return response()->json([
                    'message' => 'Friend request accepted.',
                    'record' => $relationship,
                ]);

            case 'denied':

                if ($relationship->status !== 'pending') {
                    break;
                }

                // if ($relationship->receiver !== $currentUserId) {
                //     return response()->json([
                //         'message' => 'You cannot deny your own friend request.',
                //     ], 400);
                // }

                $relationship->delete();

                return response()->json([
                    'message' => 'Friend request denied.',
                ]);

            case 'unfriend':

                if ($relationship->status !== 'friend') {
                    break;
                }

                $relationship->delete();

                return response()->json([
                    'message' => 'User unfriended.',
                ]);

            case 'block':

                // Remove whatever relationship exists
                if ($relationship) $relationship->delete();

                $blocked = Friend::create([
                    'sender'   => $currentUserId,
                    'receiver' => $foreignUserId,
                    'status'   => 'blocked',
                ]);

                // Achievement
                AchievementProgress::progress('block-user', 1);

                return response()->json([
                    'message' => 'User blocked.',
                    'record' => $blocked,
                ]);

            case 'unblock':

                if ($relationship->status !== 'blocked') {
                    break;
                }

                if ($relationship->sender !== $currentUserId) {
                    return response()->json([
                        'message' => 'You have been blocked by this user. Ask them to unblock you before interacting with them.',
                    ], 403);
                }

                $relationship->delete();

                return response()->json([
                    'message' => 'User unblocked.',
                ]);
        }

        return response()->json([
            'message' => "Action '{$action}' is not valid for relationship status '{$relationship->status}'.",
        ], 400);
    }
}
