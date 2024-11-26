<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>{{ env('APP_NAME') }}</title>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
</head>

<body>
    <!-- Navigation Bar -->
    @if (request()->path() !== 'login')
        <nav class="bg-white/80 backdrop-blur-sm border-b border-green-100 fixed w-full z-50">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="{{ route('home.index') }}" class="flex items-center">
                    <img src="{{ asset('img/appchara-logo.png') }}" alt="" class="h-10">
                </a>
                <div class="hidden w-full md:block md:w-auto">
                    <ul
                        class="font-medium flex flex-col p-4 md:p-0 mt-4 rounded-lg md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-transparent">
                        <li><a href="{{ route('home.index') }}"
                                class="block py-2 pl-3 pr-4 text-green-800 rounded hover:text-green-600 md:p-0 transition-colors">Home</a>
                        </li>
                        {{-- <li><a href="#specialties"
                                class="block py-2 pl-3 pr-4 text-green-800 rounded hover:text-green-600 md:p-0 transition-colors">Specialties</a>
                        </li> --}}
                        <li><a href="{{ route('reservation-form.form') }}"
                                class="block py-2 pl-3 pr-4 text-green-800 rounded hover:text-green-600 md:p-0 transition-colors">Order</a>
                        </li>
                        {{-- <li><a href="#contact"
                                class="block py-2 pl-3 pr-4 text-green-800 rounded hover:text-green-600 md:p-0 transition-colors">Contact</a>
                        </li> --}}
                    </ul>
                </div>
            </div>
        </nav>
    @endif
    <div class="pt-20 min-h-screen">
        {{ $slot }}
    </div>

    <footer class="bg-gradient-to-r from-green-900 to-yellow-900 text-white py-8">
        <div class="max-w-screen-xl mx-auto px-4">
            <div class="text-center">
                <p>&copy; 2024 AppChara. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>
