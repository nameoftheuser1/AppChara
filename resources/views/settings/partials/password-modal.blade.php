<div id="passwordModal" class="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full z-50 hidden">
    <div
        class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-gradient-to-b from-yellow-400 to-green-500">
        <div class="bg-white rounded-lg shadow-xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Change Password</h2>
                <button id="closePasswordModal" class="text-gray-600 hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.change-password') }}">
                @csrf
                <div class="mb-4">
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" name="new_password" id="new_password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                        required />
                </div>
                <div class="mb-4">
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm New Password
                    </label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                        required />
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" id="cancelPasswordModal"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-yellow-400 to-green-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
