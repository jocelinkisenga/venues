<x-guest-layout>
<div class="py-16 bg-white text-gray-900 dark:bg-black dark:text-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
{{--         
        <div class="text-center mb-16">
            <h2 class="text-primary font-semibold tracking-wide uppercase">Affordable prices</h2>
            <p class="mt-2 text-4xl font-extrabold text-base-content sm:text-5xl text-gray-900 dark:text-white">
                
            </p>
            <p class="mt-2 max-w-2xl text-xl text-base-content/70 mx-auto">
               
            </p>
        </div> --}}

        @include('components.pricing')

        <p class="text-center mt-12 text-sm text-base-content/50 italic">
            
        </p>
    </div>
</div>
</x-guest-layout>