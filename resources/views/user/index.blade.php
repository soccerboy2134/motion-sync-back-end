@extends('layout')

@section('head')@endsection

@section('Login', 'MotionSync Admin Panel')

@section('content')
<div>
    <x-panel>
        <h1 class="text-3xl ">Admin Panel</h1>
        <h2 class="text-2xl">Login</h2>

        <div class="text-xl mt-20">
            <form>
                <div>
                    <label>Username</label> <br>
                    <input type="text" class="border-b-2 border-b-gray-100 focus:outline-none focus:ring-0" value="test">
                </div>

                <div>
                    <label>Password</label> <br>
                    <input type="password">
                </div>

                <button type="submit">Login</button>
            </form>
        </div>
    </x-panel>
</div>
@endsection