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
    public function store(StoreWorkOutRequest $request)
    {
        $request = $request->validated();

        $WorkOut = WorkOut::create($request);

        return $WorkOut;
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
     

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {   

        $WorkOut = WorkOut::find($id);
        $WorkOut->delete();

        return "success :c";
    }
}
