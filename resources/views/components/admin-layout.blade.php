<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ env('APP_NAME') }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</head>

<body class="bg-gray-50">
    <!-- Mobile Menu Button -->
    <button data-drawer-target="sidebar" data-drawer-toggle="sidebar" aria-controls="sidebar" type="button"
        class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
        </svg>
    </button>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full md:translate-x-0"
        aria-label="Sidebar">
        <div class="flex flex-col h-full px-3 py-4 overflow-y-auto bg-gradient-to-b from-yellow-400 to-green-500">
            <div class="flex items-center mb-5 p-2">
                <span class="text-white text-2xl font-bold mx-auto">AppChara</span>
            </div>
            <ul class="space-y-2 font-medium flex-grow">
                <li>
                    <a href="#"
                        class="flex items-center p-3 text-white rounded-lg hover:bg-white/25 hover:shadow-md transition-all duration-200 group">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 22 21">
                            <path
                                d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                            <path
                                d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                        </svg>
                        <span class="ml-3 font-medium">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index') }}"
                        class="flex items-center p-3 text-white rounded-lg hover:bg-white/25 hover:shadow-md transition-all duration-200 group
                        {{ request()->routeIs('products.index', 'products.create', 'products.edit') ? 'bg-white/25 shadow-md' : '' }}">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 18 20">
                            <path
                                d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 4a2 2 0 1 1 4 0v1H7V4Zm-.5 5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" />
                        </svg>
                        <span class="ml-3 font-medium">Product Management</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center p-3 text-white rounded-lg hover:bg-white/25 hover:shadow-md transition-all duration-200 group">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M17.876.517A1 1 0 0 0 17 0H3a1 1 0 0 0-.876.517A1 1 0 0 0 2 1.5V6h16V1.5a1 1 0 0 0-.124-.983Z" />
                            <path
                                d="M2 7v11.5a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V7H2Zm6 8H4v-2h4v2Zm0-4H4v-2h4v2Zm6 4h-4v-2h4v2Zm0-4h-4v-2h4v2Z" />
                        </svg>
                        <span class="ml-3 font-medium">Sales</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center p-3 text-white rounded-lg hover:bg-white/25 hover:shadow-md transition-all duration-200 group">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M11.074 4 8.442.408A.5.5 0 0 0 8 .188a.5.5 0 0 0-.442.22L4.926 4H1.5A1.5 1.5 0 0 0 0 5.5v9A1.5 1.5 0 0 0 1.5 16h17a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 18.5 4h-7.426ZM8 13.5a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7Z" />
                        </svg>
                        <span class="ml-3 font-medium">Purchases</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center p-3 text-white rounded-lg hover:bg-white/25 hover:shadow-md transition-all duration-200 group">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z" />
                            <path
                                d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1.969 1.969 0 0 0 1.933 2h12.134A1.97 1.97 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.513.81l-3.4.679a2.947 2.947 0 0 1-1.85-.227 2.96 2.96 0 0 1-1.635-3.257l.681-3.397Z" />
                            <path
                                d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.679 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.359-4.236 4.237-1.7.339.341-1.699Z" />
                        </svg>
                        <span class="ml-3 font-medium">Reports</span>
                    </a>
                </li>
            </ul>

            <!-- Bottom Section for Settings and Logout -->
            <div class="pt-2 mt-2 border-t border-white/20">
                <ul class="space-y-2 font-medium">
                    <li>
                        <a href="#"
                            class="flex items-center p-3 text-white rounded-lg hover:bg-white/25 hover:shadow-md transition-all duration-200 group">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7.75 4H19M7.75 4a2.25 2.25 0 0 1-4.5 0m4.5 0a2.25 2.25 0 0 0-4.5 0M1 4h2.25m13.5 6H19m-2.25 0a2.25 2.25 0 0 1-4.5 0m4.5 0a2.25 2.25 0 0 0-4.5 0M1 10h11.25m-4.5 6H19M7.75 16a2.25 2.25 0 0 1-4.5 0m4.5 0a2.25 2.25 0 0 0-4.5 0M1 16h2.25" />
                            </svg>
                            <span class="ml-3 font-medium">Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center p-3 text-white rounded-lg hover:bg-white/25 hover:shadow-md transition-all duration-200 group">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 16 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 8h11m0 0-4-4m4 4-4 4m-5 3H3a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h3" />
                            </svg>
                            <span class="ml-3 font-medium">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="p-4 md:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
