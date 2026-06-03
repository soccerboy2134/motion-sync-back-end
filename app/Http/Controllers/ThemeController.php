<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreThemeRequest;
use App\Models\Theme;
use Illuminate\Http\Request;
use Nette\NotImplementedException;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        throw new NotImplementedException();
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        throw new NotImplementedException();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $theme = Theme::find($id);
        $theme->delete();

        return "success";
    }
}

