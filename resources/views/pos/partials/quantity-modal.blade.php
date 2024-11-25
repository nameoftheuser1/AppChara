<div id="quantityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Add to Cart</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="modalProductName"></p>
                <p class="text-sm text-gray-500">Available Stock: <span id="modalStock"></span></p>

                <form action="{{ route('pos.add-item') }}" method="POST" id="addToCartForm">
                    @csrf
                    <input type="hidden" name="product_id" id="modalProductId">
                    <div class="mt-4 flex items-center justify-center space-x-3">
                        <button type="button" onclick="decrementQuantity()"
                            class="px-3 py-1 bg-gray-200 rounded-lg">-</button>
                        <input type="number" name="quantity" id="quantityInput" value="1" min="1"
                            class="w-20 text-center border rounded-lg px-2 py-1">
                        <button type="button" onclick="incrementQuantity()"
                            class="px-3 py-1 bg-gray-200 rounded-lg">+</button>
                    </div>

                    <div class="mt-4">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Add to Cart
                        </button>
                        <button type="button" onclick="closeQuantityModal()"
                            class="ml-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
