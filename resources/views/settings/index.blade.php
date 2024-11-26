<x-admin-layout>
    @if (session('success'))
        <div class="mb-4 text-green-600 bg-green-100 p-3 rounded">
            {{ session('success') }}
        </div>
    @elseif ($errors->any())
        <div class="mb-4 text-red-600 bg-red-100 p-3 rounded">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <!-- Trigger Button for Modal -->
    <button id="openModal" class="px-4 py-2 bg-blue-500 text-white rounded">Change Password</button>

    <!-- Modal Structure -->
    <div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 hidden">
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-2xl mb-4">Change Password</h2>

                <!-- Success and Error Messages -->


                <form method="POST" action="{{ route('admin.change-password') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="new_password" id="new_password"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required />
                    </div>

                    <div class="mb-4">
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                            New Password</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required />
                    </div>

                    <div class="flex justify-end">
                        <button type="button" id="closeModal"
                            class="px-4 py-2 bg-gray-500 text-white rounded mr-2">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Open the modal
        document.getElementById('openModal').addEventListener('click', function() {
            document.getElementById('passwordModal').classList.remove('hidden');
        });

        // Close the modal
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('passwordModal').classList.add('hidden');
        });

        // Close the modal if clicked outside of it
        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('passwordModal')) {
                document.getElementById('passwordModal').classList.add('hidden');
            }
        });
    </script>
</x-admin-layout>
