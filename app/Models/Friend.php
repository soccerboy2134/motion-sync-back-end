<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

#[Fillable(['sender', 'receiver', 'status'])]
class Friend extends Model
{
    use HasFactory;

    public static function hasRecords(string $foreign_user_id) {
        $user_id = Auth::user()->id;

        $possibleFriendships = Friend::query()
            ->where(function ($query) use ($user_id, $foreign_user_id) {
                $query->where('sender', $user_id)
                      ->where('receiver', $foreign_user_id);
            })
            ->orWhere(function ($query) use ($user_id, $foreign_user_id) {
                $query->where('sender', $foreign_user_id)
                      ->where('receiver', $user_id);
            })
        ->get();

        return $possibleFriendships;
    }

    public static function getFriends(string $user_id) {
        $friendships = Friend::query()
        ->where(function ($query) use ($user_id) {
            $query->where(function ($q) use ($user_id) {
                $q->where('sender', $user_id);
                  
            })
            ->orWhere(function ($q) use ($user_id) {
                $q->where('receiver', $user_id);
            });
        })
        ->where('status', 'friend')
        ->get();

        return $friendships;
    }

    public function user() {
        return $this->belongsToMany(User::class);
    }

    public function senderUser() {
        return $this->belongsTo(User::class, 'sender');
    }

    public function receiverUser() {
        return $this->belongsTo(User::class, 'receiver');
    }
}