<x-layout>
    <div id="home">
        <div id="default-carousel" class="relative w-full" data-carousel="slide">
            <!-- Carousel wrapper -->
            <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
                <!-- Item 1 -->
                <div class="hidden duration-3000 ease-in-out" data-carousel-item>
                    <img src="{{ asset('img/calaca.webp') }}"
                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="Lomi">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-green-900/70 to-yellow-900/70 flex items-center justify-center">
                        <div class="text-center text-white">
                            <h2 class="text-4xl font-bold mb-4">Conchings's Atchara and Delicacies</h2>
                            <p class="text-xl">Add a Zing to Every Meal with Our Freshly Made Atchara!</p>
                        </div>
                    </div>
                </div>
                {{-- <div class="hidden duration-3000 ease-in-out" data-carousel-item>
                    <img src="/api/placeholder/1200/600"
                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="Lomi">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-green-900/70 to-yellow-900/70 flex items-center justify-center">
                        <div class="text-center text-white">
                            <h2 class="text-4xl font-bold mb-4">Ampalaya</h2>
                            <p class="text-xl">Experience the rich and hearty flavors of our signature dish</p>
                        </div>
                    </div>
                </div> --}}
                <!-- Additional carousel items with same gradient overlay -->
                <!-- Item 2 and 3 similar structure -->
            </div>
            <!-- Slider controls -->
            {{-- <button type="button"
                class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-prev>
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-green-800/30 group-hover:bg-green-800/50 group-focus:ring-4 group-focus:ring-green-800 group-focus:outline-none">
                    <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 1 1 5l4 4" />
                    </svg>
                </span>
            </button>
            <button type="button"
                class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-next>
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-green-800/30 group-hover:bg-green-800/50 group-focus:ring-4 group-focus:ring-green-800 group-focus:outline-none">
                    <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                </span>
            </button> --}}
        </div>
    </div>

    <!-- Specialties Section -->
    <section id="specialties" class="py-16 px-4 max-w-screen-xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-12 text-green-800">Our Specialties</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Food Items with updated styling -->
            <div
                class="bg-white/80 backdrop-blur-sm rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow border border-green-100">
                <img src="{{ asset('img/papaya-default.jpg') }}" alt="Lomi" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2 text-green-800">Papaya</h3>
                    <p class="text-green-700">Papaya atchara is a Filipino-style pickled green papaya. It pairs
                        perfectly with grilled and fried dishes, enhancing the overall dining experience.</p>
                </div>
            </div>
            <div
                class="bg-white/80 backdrop-blur-sm rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow border border-green-100">
                <img src="{{ asset('img/ampalaya.jpg') }}" alt="Lomi" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2 text-green-800">Ampalaya</h3>
                    <p class="text-green-700">Atcharang ampalaya, or pickled bitter gourd, is a tangy and refreshing
                        side dish that pairs perfectly with fried foods.</p>
                </div>
            </div>
            <div
                class="bg-white/80 backdrop-blur-sm rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow border border-green-100">
                <img src="{{ asset('img/ubod.jpg') }}" alt="Lomi" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2 text-green-800">Ubod</h3>
                    <p class="text-green-700">The edible pith derived from coconut trunks</p>
                </div>
            </div>

            <!-- Additional food items with same styling -->
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 bg-gradient-to-br from-green-100/50 to-yellow-100/50">
        <div class="max-w-screen-xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-6 text-green-800">About Casiana Camson Villamar</h2>
                    <p class="text-green-700 mb-4">
                        Casiana Camson Villamar, born in 1936, was the founder of Conching's Atchara and Delicacies. She
                        began her entrepreneurial journey at the age of 31, initially establishing the business in
                        Poblacion 2. Later, it was relocated to Poblacion 5, where it continued to grow and serve the
                        community. Casiana passed away on August 24, 2021, leaving behind a legacy of perseverance. She
                        will always be remembered for her contribution and inspiration to many.
                    </p>
                </div>
                <div>
                    <img src="{{ asset('img/picture.jpg') }}" alt="Calaca City" class="rounded-lg shadow-lg w-full">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    {{-- <section id="contact" class="py-16">
        <div class="max-w-screen-xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-green-800">Contact Us</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4 text-green-800">Get in Touch</h3>
                    <p class="text-green-700 mb-4">
                        Want to learn more about our local delicacies or plan a food trip to Calaca?
                        We'd love to hear from you!
                    </p>
                    <div class="space-y-2">
                        <p class="flex items-center text-green-700">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            +63 123 456 7890
                        </p>
                        <p class="flex items-center text-green-700">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            info@calacadelights.com
                        </p>
                    </div>
                </div>
                <div>
                    <form class="space-y-4">
                        <div>
                            <label class="block text-green-700 mb-2" for="name">Name</label>
                            <input type="text" id="name"
                                class="w-full p-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-green-700 mb-2" for="email">Email</label>
                            <input type="email" id="email"
                                class="w-full p-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-green-700 mb-2" for="message">Message</label>
                            <textarea id="message" rows="4"
                                class="w-full p-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                required></textarea>
                        </div>
                        <button type="submit"
                            class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-2 rounded-lg hover:from-green-700 hover:to-green-800 transition-colors">Send
                            Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Footer -->

</x-layout>
