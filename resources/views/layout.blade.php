<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="/favicon.ico">
    @yield('head')
    <title>@yield('Unknown Page', 'MotionSync Admin Panel')</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>

<body class="bg-gray-900">    
    <x-header/>

    <div class="flex flex-col 
            justify-center pt-[15vh]
            sm:justify-center sm:pt-0
            items-center 
            h-[calc(100vh-4rem)] space-y-4 
            text-gray-100 w-8/12 
            mx-auto">
        @yield('content')
    </div>
</body>

</html>