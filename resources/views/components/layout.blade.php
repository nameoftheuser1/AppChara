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

            <!-- Mobile Hamburger Button -->
            <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-green-800 rounded-lg md:hidden hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-200" aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>

            <!-- Desktop Navigation -->
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 rounded-lg md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-transparent">
                    <li><a href="{{ route('home.index') }}" class="block py-2 pl-3 pr-4 text-green-800 rounded hover:text-green-600 md:p-0 transition-colors">Home</a></li>
                    <li><a href="{{ route('reservation-form.form') }}" class="block py-2 pl-3 pr-4 text-green-800 rounded hover:text-green-600 md:p-0 transition-colors">Order</a></li>
                </ul>
            </div>

            <!-- Mobile Sidebar -->
            <div id="navbar-default" class="fixed top-0 left-0 z-40 w-64 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white/90 backdrop-blur-sm md:hidden" tabindex="-1" aria-labelledby="drawer-navigation-label">
                <h5 id="drawer-navigation-label" class="text-base font-semibold text-green-800 uppercase">Menu</h5>
                <button type="button" data-drawer-hide="navbar-default" aria-controls="navbar-default" class="text-green-800 bg-transparent hover:bg-green-200 hover:text-green-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close menu</span>
                </button>
                <div class="py-4">
                    <ul class="space-y-2 font-medium">
                        <li>
                            <a href="{{ route('home.index') }}" class="flex items-center p-2 text-green-800 rounded-lg hover:bg-green-100 group">
                                <span class="ms-3">Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reservation-form.form') }}" class="flex items-center p-2 text-green-800 rounded-lg hover:bg-green-100 group">
                                <span class="ms-3">Order</span>
                            </a>
                        </li>
                    </ul>
                </div>
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