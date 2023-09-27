<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nova+Flat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="{{ asset('/favicon.ico') }}" type="image/x-icon">

    <title>@yield('title')</title>
</head>

<body>
    <header>
        <nav class="container mx-auto p-5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-5xl">
                <span class="text-tealLight font-nova-flat">AI </span>Studio
            </a>
            <div class="hidden md:flex space-x-6 md:space-x-2">
                <ul class="flex flex-row space-x-8">
                    @php
                        $navStyle = 'hover:text-tealLight transition-colors';
                    @endphp
                    <li class="{{ $navStyle }}"><a href="{{ route('home') }}#f">Feature</a></li>
                    <li class="{{ $navStyle }}"><a href="{{ route('home') }}#show">Showcase</a></li>
                    <li class="{{ $navStyle }}"><a href="{{ route('home') }}#news">News</a></li>
                    <li class="{{ $navStyle }}"><a href="{{ route('home') }}#faq">Faq</a></li>
                    <li class="{{ $navStyle }}"><a href="">Contact</a></li>
                </ul>
            </div>

            <a href="#foot" class="hidden bg-teal-400 text-white p-3 rounded-full md:block">Get Started</a>

        </nav>
    </header>

    <main class="mx-auto overflow-hidden ">
        @yield('content')
    </main>

    <footer class=" mt-8 mx-auto max-h-fit" id="foot">
        <div class="bg-gradient-to-r from-tealLight to-orange-200 p-16 ">
            <div class="flex flex-col items-center ">
                <h1 class="text-2xl font-semibold text-white text-center ">What are you waiting for? Download Now!</h1>

                <div class="flex max-w-md justify-center mt-5">
                    <img src="{{ asset('img/google.png') }}" class="w-1/3 h-1/2 mb-5 md:w-1/4 md:h-1/2" alt="">
                    <img src="{{ asset('img/apple.png') }}" class="w-1/3 h-1/2 md:w-1/4 md:h-1/2" alt="">
                </div>
            </div>
            <div class="flex justify-center mt-10">
                <div>

                    <img src="{{ asset('img/logo.svg') }}" class="w-1/3 h-1/2 mb-5 md:w-1/4 md:h-1/2" alt="">


                    <p class="text-xs text-white">©SoftGrove-2023</p>
                </div>

                <div class="ml-20">
                    <h1 class="text-white font-semibold">COMPANY</h1>
                    <br>
                    <a href="" class="font-light text-gray-100 pt-5">SoftGrove</a>
                </div>

                <div class="ml-20 text-white font-semibold">
                    <h1>LEGAL</h1>
                    <div class="flex flex-col font-light">
                        <a href="{{ route('privacy') }}" class="text-gray-100 pt-5">Privacy Policy</a>
                        <a href="" class="text-gray-100 pt-5">Terms of Use</a>
                    </div>
                </div>
                <div class="hidden ml-20 text-white font-semibold md:block">
                    <h1>SERVICES</h1>
                    <div class="flex flex-col font-light">
                        <a href="" class="text-gray-100 pt-5">App Designing</a>
                        <a href="" class="text-gray-100 pt-5">Developing</a>
                    </div>
                </div>



            </div>
        </div>
    </footer>

</body>

</html>
