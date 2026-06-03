<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreThemeRequest;
use App\Models\Theme;

class ThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $themes = Theme::all();
        return $themes;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThemeRequest $request)
    {
        $request = $request->validated();

        $theme = Theme::create($request);

        return $theme;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $theme = Theme::find($id);
        $theme->delete();

        return response()->json([
            'message' => 'The theme was successfully deleted.'
        ], 200);
    }
}

