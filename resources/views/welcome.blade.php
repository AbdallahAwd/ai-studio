@extends('layout.app')

@section('title', 'AI Studio')

@section('content')
    {{-- <section class="relative container mt-10">
        <div class="flex flex-col-reverse justify-between items-center lg:flex-row md:flex-row">
            <div class="flex flex-col max-w-lg p-15">
                <div class="">
                    <h1 class="text-5xl font-semibold mb-8 leading-15">Elevate your content with <span
                            class="text-tealLight">AI
                            Studio</span> – Where professional AI creativity comes to life</h1>
                    <p class="text-gray-400 leading-12">Revolutionize content creation with our app, seamlessly blending
                        human
                        ingenuity and
                        AI innovation for stunning results.</p>
                </div>
            </div>

        </div>
        <br>

    </section> --}}

    <div class="container mx-auto">
        <section class="  flex flex-col-reverse md:flex-row md:justify-around">
            <div class="max-w-lg ">
                <h1 class="text-5xl font-semibold mb-8  m-5 leading-snug text-center md:text-left md:mt-0">Elevate your
                    content
                    with <span class="text-tealLight">AI
                        Studio</span> – Where professional AI creativity comes to life</h1>
                <p class="text-gray-400 leading-12 text-center md:text-left">Revolutionize content creation with our app,
                    seamlessly blending
                    human
                    ingenuity and
                    AI innovation for stunning results.</p>
                <div class="flex flex-col mt-7  justify-start items-center md:items-start md:space-x-6 md:flex-row item">
                    <img src="{{ asset('img/google.png') }}" class="w-1/2 h-1/2 mb-5 md:w-1/4 md:h-1/2" alt="">
                    <img src="{{ asset('img/apple.png') }}" class="w-1/2 h-1/2 md:w-1/4 md:h-1/2" alt="">
                </div>
                <div>
                    <img src="{{ asset('img/decoration.png') }}" class="hidden mt-5 max-w-md h-28 md:block" alt="">
                </div>
            </div>

            <div>
                <img src="{{ asset('img/f_photo.png') }}" class="lg:max-w-md" alt="">
            </div>

        </section>
        {{-- Info Section --}}
        <section class="">
            <div class="container flex justify-between max-w-2xl mx-auto mt-3">
                <div class="p-5">
                    <h1 class="text-2xl text-teal-600">+1M</h1>
                    <div class="flex items-center">

                        <i class="fa-solid fa-cloud-arrow-down pr-3 text-gray-400"></i>

                        <h3 class="text-xl text-gray-400">Downloads</h3>
                    </div>
                </div>
                <div class="border-l border-gray-300 h-16 mt-4"></div>
                <div class="p-5">
                    <h1 class="text-2xl text-teal-600">+500K</h1>
                    <div class="flex items-center">

                        <i class="fa-regular fa-circle-check pr-2 text-gray-400"></i>

                        <h3 class="text-xl text-gray-400">Transaction</h3>
                    </div>
                </div>
                <div class="border-l border-gray-300 h-16 mt-4"></div>
                <div class="p-5">
                    <h1 class="text-2xl text-teal-600">+10K</h1>
                    <div class="flex items-center">

                        <i class="fa-regular fa-star pr-2 text-gray-400"></i>

                        <h3 class="text-xl text-gray-400">Rating</h3>
                    </div>
                </div>
            </div>
        </section>
        {{-- Features --}}
        <section class="mt-10 flex flex-col">
            <h1 class="text-3xl font-semibold mx-auto" id="f">Features</h1>
            <div class="bg-gradient-to-r from-tealLight to-orange-200 h-2 w-10 rounded-full mx-auto mt-1">

            </div>
            <div class="flex flex-col items-center text-gray-400  md:flex-row">
                <div class="">
                    <img src="{{ asset('img/feature1.svg') }}" class="lg:max-w-2xl " alt="">
                </div>
                <p class="text-center p-5 text-xl md:p-0">Unleash the power of AI to replicate voices flawlessly. From
                    iconic
                    impressions
                    to personalized messages,
                    redefine communication and creativity like never before.</p>

            </div>

        </section>
    </div>
    {{-- Second --}}
    <section class="container mx-auto">
        <div>
            <div class="flex items-center flex-col text-center p-5 md:flex-row-reverse md:text-left ">
                {{-- <img src="{{ asset('img/custom_bg1.svg') }}" class="max-h-min w-fit" alt=""> --}}

                <img src="{{ asset('img/feature2.svg') }}" class=" max-w-md  lg:max-w-3xl md:right-9 " alt="">
                <div>

                    <h2 class=" max-w-2xl   font-semibold text-2xl ">Subtitles & Global Video
                        Translation
                        –
                        Elevate
                        Your
                        Content!</h2>
                    <p class=" mt-8 max-w-2xl   text-md text-gray-400">Empower
                        your videos
                        with
                        our app's dynamic dual feature: effortlessly add subtitles and seamlessly
                        translate into any language, unlocking global reach and engagement.</p>
                </div>


            </div>

            {{-- <div class="md:hidden">
                <h2 class=" z-12  text-center max-w-2xl p-12 font-semibold text-2xl mb-5 ">
                    Subtitles
                    &
                    Global
                    Video
                    Translation
                    –
                    Elevate
                    Your
                    Content!</h2>

            </div> --}}
        </div>

    </section>


    {{-- ShowCase --}}
    <section class=" mx-auto">
        <div>
            <div class="relative">
                {{-- <img src="{{ asset('img/custom_bg1.svg') }}" class="max-h-min w-fit" alt=""> --}}
                <img src="{{ asset('img/custom_bg2.svg') }}" class="max-h-min w-fit" alt="">

                <div class="hidden lg:block">

                    <h2 id="show"
                        class="absolute z-11 top-10  max-w-2xl left-l text-white font-semibold text-2xl md:top-40 ">
                        Showcase
                    </h2>
                    <p
                        class=" absolute z-11 top-10  max-w-2xl left-1/4 text-white font-light text-md text-center md:top-56 ">
                        Upgrade your videos instantly: Add dynamic subtitles and translate for a global audience with our
                        game-changing app.
                    </p>
                </div>

                <img src="{{ asset('img/welcome.png') }}" class="absolute z-20 bottom-0 left-abs max-w-xs h-1/2"
                    alt="">
                <img src="{{ asset('img/home.png') }}" class="absolute z-20 lef bottom-2  max-w-xs h-1/2" alt="">
                <img src="{{ asset('img/subs.png') }}" class="absolute z-20 rig bottom-2  max-w-xs h-1/2" alt="">
                <img src="{{ asset('img/script.png') }}" class="absolute z-20 rig-l bottom-5  max-w-xs h-1/2"
                    alt="">
                <img src="{{ asset('img/cloner.png') }}" class="absolute z-20 lef-l bottom-5  max-w-xs h-1/2"
                    alt="">


            </div>

            <div class="md:hidden">
                <p class=" z-12  text-center max-w-lg p-12 font-thin text-sm mb-5 ">
                    Upgrade your videos instantly: Add dynamic subtitles and translate for a global audience with our
                    game-changing app.</p>

            </div>
        </div>

    </section>
    <br>
    {{-- FAQ --}}
    <section class="container mx-auto mt-5" id="faq">
        <div class="flex flex-col md:ml-10 justify-around md:flex-row">
            {{-- faq --}}
            <div class="p-8">
                <div cka>
                    <h1 class="text-3xl font-semibold">FAQ</h1>
                    <div class="bg-gradient-to-r from-tealLight to-orange-200 h-1 w-10 rounded-full mt-1">
                    </div>
                </div>

                <img src="{{ asset('img/faq.png') }}" class="hidden max-w-xs mt-5 md:block" alt="">

            </div>
            {{-- questions --}}
            <div class="ml-5  md:w-1/2">
                @php
                    $faq = [
                        'What is AI Studio?' => 'AI Studio is an innovative application that harnesses the power of artificial intelligence to enhance your content creation experience...',
                        'How can AI Studio enhance my content?' => 'AI Studio empowers you to take your creativity to new heights. With the ability to flawlessly replicate voices...',
                        'What is the voice replication feature in AI Studio?' => "AI Studio's voice replication feature enables you to replicate voices with remarkable accuracy using AI technology...",
                        'How does the subtitle feature work?' => 'The subtitle feature in AI Studio adds a dynamic layer to your videos. It automatically generates subtitles for your content...',
                        'Tell me about the Global Video Translation feature.' => "AI Studio's Global Video Translation feature revolutionizes the way you reach your audience. It allows you to translate your video content...",
                        'How easy is it to use AI Studio?' => 'AI Studio is designed with user-friendliness in mind. Its intuitive interface and step-by-step guides make it easy for both beginners...',
                    ];
                    
                @endphp

                @foreach ($faq as $question => $answer)
                    <div class="py-2 ">
                        <div class="border rounded-lg p-4 ">
                            <div class="flex justify-between items-center cursor-pointer">
                                <h3 class="text-lg font-semibold transition-colors question ">{{ $question }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform transition-transform arrow"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="hidden mt-2 text-gray-600 answer">{{ $answer }}</div>
                        </div>
                    </div>
                @endforeach

            </div>


        </div>
        <script>
            const expandableItems = document.querySelectorAll('.border');
            expandableItems.forEach(item => {
                const toggleButton = item.querySelector('.flex');
                const answer = item.querySelector('.answer');
                const question = item.querySelector('.question');
                const arrow = item.querySelector('.arrow');

                toggleButton.addEventListener('click', () => {
                    answer.classList.toggle('hidden');
                    const svg = toggleButton.querySelector('svg');
                    svg.classList.toggle('rotate-180');
                    question.classList.toggle('text-tealLight'); // Add this line to change text color
                    arrow.classList.toggle('text-tealLight'); // Add this line to change text color

                    // Add this line to adjust the height of the border container
                    item.style.height = answer.classList.contains('hidden') ? 'auto' : item.scrollHeight + 'px';
                });
            });
        </script>
    </section>
    {{-- Latese News --}}

    <section class="container mx-auto pt-10" id="news">
        <h1 class="flex justify-center text-3xl">
            <pre class="text-gray-400">Latest </pre>
            News
        </h1>

        <div class="flex flex-wrap justify-center mt-5">

            <div
                class="border max-w-sm bg-white rounded-lg shadow-md p-6 m-2 hover:shadow-lg hover:-translate-y-5 hover:transition-transform  cursor-pointer">
                <img src="{{ asset('img/logo.svg') }}" alt="News Img" class="max-w-full rounded-md mb-4">
                <h2 class="text-xl font-semibold mb-2">Introducing AI Studio Beta Release</h2>
                <p class="text-gray-600"> We're thrilled to announce the beta release of AI Studio, a revolutionary app
                    that harnesses the power of artificial intelligence to transform the way you communicate and create.</p>
            </div>

            <div
                class="border max-w-sm bg-white rounded-lg shadow-md p-6 m-2 hover:shadow-lg hover:-translate-y-5 hover:transition-transform cursor-pointer">
                <img src="https://moviemaker.minitool.com/images/uploads/2021/11/video-translator-thumbnail.png"
                    alt="News Img" class="max-w-full rounded-md mb-4">
                <h2 class="text-xl font-semibold mb-2"> Subtitles & Global Video Translation</h2>
                <p class="text-gray-600"> Enhance your videos instantaneously by adding dynamic subtitles that resonate
                    across languages.</p>
            </div>
            <div
                class="border max-w-sm bg-white rounded-lg shadow-md p-6 m-2 hover:shadow-lg hover:-translate-y-5 hover:transition-transform cursor-pointer">
                <img src="https://static.voices.com/wp-content/uploads/2023/03/AdobeStock_446636009-scaled-e1678456557727.jpeg"
                    alt="News Img" class="max-w-full rounded-md mb-4">
                <h2 class="text-xl font-semibold mb-2">Replicate Voices with Flawless Precision</h2>
                <p class="text-gray-600">Unleash the potential of AI to replicate voices with unprecedented accuracy.</p>
            </div>



        </div>


    </section>

@endsection
