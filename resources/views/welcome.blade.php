<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sorteo de Banco de Preguntas</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
<header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
    @if (Route::has('login'))
        <nav class="flex items-center justify-end gap-4">
            @auth
                <a
                    href="{{ url('/dashboard') }}"
                    class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                >
                    Dashboard
                </a>
            @else

            @endauth
        </nav>
    @endif
</header>
<div
    class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
    <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">

        <div
            class="leading-[20px] flex-1 p-6 pb-6 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-es-lg rounded-ee-lg lg:rounded-ss-lg lg:rounded-ee-none">
            <h1 class="font-bold text-4xl mb-3">Bienvenido a <br> DIAD</h1>
            <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">Sistema de sorteo de preguntas </p>
            <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">Para exámenes de admisión o Cepre-UNI</p>

            <ul class="flex gap-3 text-sm leading-normal mt-5">
                <li>
                    <a href="/login"
                       class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal">
                        Ingresar
                    </a>
                </li>
            </ul>
        </div>
        <div
            class="bg-[#fff2f2]  dark:bg-[#1D0002]
            border border-gray-300
            relative lg:-ms-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-e-lg! aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden">
            <img src="{{asset('assets/images/img1.png')}}">
        </div>
    </main>
</div>

@if (Route::has('login'))
    <div class="h-14.5 hidden lg:block"></div>
@endif
</body>
</html>
