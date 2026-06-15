<?php

namespace App\Http\Controllers;

use App\Models\Skin;
use App\Models\UnlockedSkin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    // this could be improved.. it should be..
    public function show(String $id) {
        $skin = Skin::find($id);

        // return url
        $url = Storage::url($skin->location);
        return "192.168.129.126:8000" . $url . '.png';
    }

    // returns only unlocked skins
    public function unlocked() {
        return UnlockedSkin::where('user_id', Auth::id())->with('skin')->get()->pluck('skin');
    }
}
