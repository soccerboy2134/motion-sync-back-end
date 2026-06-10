<?php

namespace App\Http\Controllers;

use App\Models\Skin;
use App\Models\UnlockedSkin;
use Auth;
use Illuminate\Http\Request;

class SkinsController extends Controller
{
    // returns all skins AND whether they are unlocked or not
    public function index() {
        // get all skins
        $skins = Skin::all();

        // get all unlocked skins for the user
        $unlockedSkins = UnlockedSkin::where('user_id', Auth::id())->pluck('skin_id')->toArray();

        // map through skins and add is_unlocked property
        $skins = $skins->map(function($skin) use ($unlockedSkins) {
            $skin->is_unlocked = in_array($skin->id, $unlockedSkins);
            return $skin;
        });

        return response()->json($skins);
    }

    // returns only unlocked skins
    public function unlocked() {
        return UnlockedSkin::where('user_id', Auth::id())->with('skin')->get()->pluck('skin');
    }
}
