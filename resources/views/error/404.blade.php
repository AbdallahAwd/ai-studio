@extends('layout.app') <!-- Extend your layout if you have one -->

@section('content')
    <div class="flex items-center justify-center text-center p-24">

        <img src="{{ asset('img/404.svg') }}" class="w-1/6 h-1/4" alt="">

        <div class="flex flex-col items-start p-5">
            <h1 class="text-2xl font-semibold">This page could not be found</h1>
            <p class="max-w-xs text-start text-gray-400 p-2">You can either stay and chill here, or go back to the beginning.
            </p>
        </div>
    </div>
@endsection
