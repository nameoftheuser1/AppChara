{{-- Personal Information Fields --}}
<div class="mb-6 bg-white p-6 rounded-lg shadow-lg border border-gray-100">
    <h2 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        Personal Information
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" id="name" name="name" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact
                Number</label>
            <input type="text" id="contact_number" name="contact_number" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('contact_number') border-red-500 @enderror">
            @error('contact_number')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <input type="email" id="email" name="email" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="pick_up_date" class="block text-sm font-medium text-gray-700">Pick-Up Date</label>
            <input type="date" id="pick_up_date" name="pick_up_date" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('pick_up_date') border-red-500 @enderror">
            @error('pick_up_date')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
        {{-- <div>
            <label for="coupon" class="block text-sm font-medium text-gray-700">Coupon Code</label>
            <input type="text" id="coupon" name="coupon"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('coupon') border-red-500 @enderror">
            @error('coupon')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div> --}}
    </div>
</div>
