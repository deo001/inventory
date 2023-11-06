<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }} | {{ config('app.name') }}</title>

        @filamentStyles
        @vite(['resources/css/app.css', 'resources/css/components.css'])
    </head>
    <body class="">
        <livewire:layout.navigation />
        <div class="py-4 sm:py-6">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">

                {{-- heading and subheading --}}
                @if(isset($heading) && isset($subheading))
                    <div class="mx-auto max-w-2xl sm:text-center">
                        <h1 class="text-3xl text-center my-2"> {{ $heading }}</h1>
                        <p {{ $subheading->attributes->class(['text-lg mt-2 text-slate-600 text-center']) }}> {{ $subheading }}</p>
                    </div>
                {{-- can be hero section images --}}
                @else
                    {{ $content }}
                @endif
                
                <div class="mx-auto mt-10 max-w-2xl sm:mt-12 lg:mx-0 lg:max-w-none">
                    {{ $slot }}
                </div>
            </div>
        </div>
        <livewire:layout.footer />
        @filamentScripts
        @vite('resources/js/app.js')
    </body>
</html>
