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

    <div class="flex flex-col gap-y-2">
        <!-- Password Change Button -->
        <!-- Password Change Button -->
        <button id="openPasswordModal"
            class="flex items-center p-3 text-white rounded-lg hover:bg-green-600 hover:shadow-md transition-all duration-200 group bg-green-500">
            <svg class="w-5 h-5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 16 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7.5 1a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7ZM3 10h9a2 2 0 0 1 2 2v3H1v-3a2 2 0 0 1 2-2Z" />
            </svg>
            <span class="font-medium">Change Password</span>
        </button>

        <!-- Email Change Button -->
        <button id="openEmailModal"
            class="flex items-center p-3 text-white rounded-lg hover:bg-green-600 hover:shadow-md transition-all duration-200 group bg-green-500">
            <svg class="w-5 h-5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path d="M2.5 3A1.5 1.5 0 0 0 1 4.5V5h18v-.5A1.5 1.5 0 0 0 17.5 3h-15Z" />
                <path fill-rule="evenodd"
                    d="M19 7.5V16.5A1.5 1.5 0 0 1 17.5 18h-15A1.5 1.5 0 0 1 1 16.5V7.645c.37.275.861.52 1.462.71C3.833 8.701 6.132 9 10 9c3.867 0 6.167-.299 7.538-.645.6-.19 1.093-.435 1.462-.71Z"
                    clip-rule="evenodd" />
            </svg>
            <span class="font-medium">Change Email</span>
        </button>

    </div>

    <!-- Modal Structure for Password Change -->
    @include('settings.partials.password-modal')
    <!-- Modal Structure for Email Change -->
    @include('settings.partials.email-modal')

    <script>
        // Password Modal Handling
        const openPasswordModal = document.getElementById('openPasswordModal');
        const passwordModal = document.getElementById('passwordModal');
        const closePasswordModal = document.getElementById('closePasswordModal');
        const cancelPasswordModal = document.getElementById('cancelPasswordModal');

        openPasswordModal.addEventListener('click', function() {
            passwordModal.classList.remove('hidden');
        });

        [closePasswordModal, cancelPasswordModal].forEach(button => {
            button.addEventListener('click', function() {
                passwordModal.classList.add('hidden');
            });
        });

        // Email Modal Handling
        const openEmailModal = document.getElementById('openEmailModal');
        const emailModal = document.getElementById('emailModal');
        const closeEmailModal = document.getElementById('closeEmailModal');
        const cancelEmailModal = document.getElementById('cancelEmailModal');

        openEmailModal.addEventListener('click', function() {
            emailModal.classList.remove('hidden');
        });

        [closeEmailModal, cancelEmailModal].forEach(button => {
            button.addEventListener('click', function() {
                emailModal.classList.add('hidden');
            });
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === passwordModal) {
                passwordModal.classList.add('hidden');
            }
            if (event.target === emailModal) {
                emailModal.classList.add('hidden');
            }
        });
    </script>
</x-admin-layout>
