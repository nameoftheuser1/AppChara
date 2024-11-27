<script>
    function showLoadingOverlay() {
        // Create a loading overlay
        const overlay = document.createElement('div');
        overlay.id = 'loading-overlay';
        overlay.classList.add('fixed', 'inset-0', 'z-50', 'flex', 'items-center', 'justify-center', 'bg-black',
            'bg-opacity-50');

        const loader = document.createElement('div');
        loader.classList.add('bg-white', 'p-6', 'rounded-lg', 'shadow-xl', 'flex', 'flex-col', 'items-center');
        loader.innerHTML = `
            <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-green-500 mb-4"></div>
            <p class="text-gray-800 text-lg">Processing your order...</p>
            <p class="text-gray-600 text-sm mt-2">Please do not close or refresh the page</p>
        `;

        overlay.appendChild(loader);
        document.body.appendChild(overlay);
    }
</script>
