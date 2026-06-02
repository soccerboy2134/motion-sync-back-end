<div class="bg-transparent h-16 flex items-center px-3 relative text-white">
<div class="hidden sm:flex items-center w-full">
    @auth
        {{-- Left --}}
        <div class="text-xl">
            <a href="{{ route('admin.index', ['user' => auth()->user()->id]) }}" class="ml-5  {{ request()->routeIs('admin.index') ? 'underline' : '' }}">Account</a>
            <a href="{{ route('admin.index') }}" class="ml-3 {{ request()->routeIs('admin.index') ? 'underline' : '' }}">Devices</a>
        </div>
        {{-- Right --}}
        <div class="ml-auto mr-5 text-xl">
            <a href="{{ route('admin.index') }}">Log out</a>
        </div>
    @else
        {{-- Right --}}
        <div class="ml-auto mr-5 text-xl hover:bg-gray-700 hover:rounded-xl p-3">
            <a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.index') ? 'underline' : '' }}">Login</a>
        </div>
    @endauth
</div>
</div>