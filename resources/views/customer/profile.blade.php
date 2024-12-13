<x-layout>

    <body class="bg-gradient-to-br from-yellow-400 to-green-500">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
                <div class="bg-green-800 text-white p-6">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white/20 rounded-full w-16 h-16 flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold">{{ Auth::user()->username }}</h2>
                            <p class="text-sm text-green-100">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-md">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="p-6">
                    <h3 class="text-lg font-semibold text-green-800 mb-4">Reservation History</h3>

                    @if ($reservations->isEmpty())
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <p class="text-green-800">No reservations found.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-green-800">
                                <thead class="text-xs text-green-700 uppercase bg-green-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Transaction Key</th>
                                        <th scope="col" class="px-4 py-3">Pick-up Date</th>
                                        <th scope="col" class="px-4 py-3">Status</th>
                                        <th scope="col" class="px-4 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reservations as $reservation)
                                        <tr class="border-b hover:bg-green-50 transition-colors">
                                            <td class="px-4 py-3">{{ $reservation->transaction_key }}</td>
                                            <td class="px-4 py-3">{{ $reservation->pick_up_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span
                                                    class="px-2 py-1 rounded-full text-xs
                                                @switch($reservation->order->status)
                                                    @case('pending')
                                                        bg-yellow-100 text-yellow-800
                                                        @break
                                                    @case('confirmed')
                                                        bg-blue-100 text-blue-800
                                                        @break
                                                    @case('cancelled')
                                                        bg-red-100 text-red-800
                                                        @break
                                                    @case('completed')
                                                        bg-green-100 text-green-800
                                                        @break
                                                    @case('processing')
                                                        bg-orange-100 text-orange-800
                                                        @break
                                                    @case('ready to pickup')
                                                        bg-teal-100 text-teal-800
                                                        @break
                                                    @case('refunded')
                                                        bg-purple-100 text-purple-800
                                                        @break
                                                @endswitch">
                                                    {{ ucfirst($reservation->order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <a href="{{ route('check.status', ['transaction_key' => $reservation->transaction_key]) }}"
                                                    class="text-green-600 hover:text-green-800 transition-colors">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="p-6 bg-green-50 border-t border-green-200">
                    <h3 class="text-lg font-semibold text-green-800 mb-4">Account</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit"
                                class="bg-white border border-green-200 rounded-lg p-4 hover:bg-green-100 transition-colors flex items-center justify-between w-full">
                                <span class="text-green-800">Logout</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                </svg>
                            </button>
                        </form>
                        <button onclick="document.getElementById('changePasswordModal').classList.remove('hidden')"
                            class="bg-white border border-green-200 rounded-lg p-4 hover:bg-green-100 transition-colors flex items-center justify-between w-full">
                            <span class="text-green-800">Change Password</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-xl font-semibold text-green-800 mb-4">Change Password</h3>
            <!-- Form to change password -->
            <form action="{{ route('change.user-password') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-semibold text-green-800">Current
                        Password</label>
                    <input type="password" name="current_password" id="current_password"
                        class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>

                <div class="mb-4">
                    <label for="new_password" class="block text-sm font-semibold text-green-800">New Password</label>
                    <input type="password" name="new_password" id="new_password"
                        class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>

                <div class="mb-4">
                    <label for="new_password_confirmation" class="block text-sm font-semibold text-green-800">Confirm
                        New
                        Password</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                        class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>

                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-md w-full">Change
                    Password</button>
            </form>

            <!-- Close button -->
            <button onclick="document.getElementById('changePasswordModal').classList.add('hidden')"
                class="mt-4 w-full bg-gray-300 py-2 rounded-md text-gray-700">Close</button>
        </div>
    </div>

    <script>
        // Get the modal element
        const modal = document.getElementById('changePasswordModal');

        // Open the modal when the "Change Password" button is clicked
        document.querySelector(
                'button[onclick="document.getElementById(\'changePasswordModal\').classList.remove(\'hidden\')"]')
            .addEventListener('click', function() {
                modal.classList.remove('hidden');
            });

        // Close the modal when the "Close" button is clicked
        document.querySelector(
                'button[onclick="document.getElementById(\'changePasswordModal\').classList.add(\'hidden\')"]')
            .addEventListener('click', function() {
                modal.classList.add('hidden');
            });

        // Stop propagation on the modal content to prevent closing it when clicked inside
        modal.querySelector('.bg-white').addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent the click from propagating to the modal background
        });

        // Close the modal if the user clicks outside of the modal content
        modal.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
    </script>


</x-layout>
