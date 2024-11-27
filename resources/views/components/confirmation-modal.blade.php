<!-- resources/views/components/confirmation-modal.blade.php -->
<div id="confirmationModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50 h-full">
    <div class="bg-white p-6 rounded-lg w-96">
        <h3 class="text-lg font-semibold mb-4" id="confirmationTitle">{{ $title }}</h3>
        <p class="mb-4" id="confirmationMessage">{{ $message }}</p>
        <div class="flex justify-end">
            <button id="cancelButton" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</button>
            <button id="confirmButton" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Confirm</button>
        </div>
    </div>
</div>

<script>
    function showConfirmationModal(action, orderId) {
        const modal = document.getElementById('confirmationModal');
        const title = document.getElementById('confirmationTitle');
        const message = document.getElementById('confirmationMessage');
        const cancelButton = document.getElementById('cancelButton');
        const confirmButton = document.getElementById('confirmButton');

        if (action === 'cancel') {
            title.textContent = 'Cancel Order';
            message.textContent = 'Are you sure you want to cancel this order?';
        } else if (action === 'process') {
            title.textContent = 'Process Order';
            message.textContent = 'Are you sure you want to process this order?';
        }

        cancelButton.onclick = () => modal.classList.add('hidden');
        confirmButton.onclick = () => {
            document.getElementById(action + '-form-' + orderId).submit();
            modal.classList.add('hidden');
        };

        modal.classList.remove('hidden');
    }
</script>
