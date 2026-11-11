<x-app-client>
    <div class="max-w-7xl mx-auto p-6 md:p-10 space-y-12">
        
        <div class="bg-brand-amber border border-amber-200 text-brand-amber-text p-4 rounded-xl flex items-center space-x-3 text-sm font-medium">
            <svg class="h-5 w-5 flex-shrink-0 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5a1 1 0 11-2 0V4a1 1 0 011-1zm1 10a1 1 0 11-2 0 1 1 0 012 0zm-8.293 2.707a1 1 0 010-1.414L8.293 11l-1.5-1.5a1 1 0 011.414-1.414L10 9.586l1.793-1.793a1 1 0 111.414 1.414L11.414 11l1.5 1.5a1 1 0 01-1.414 1.414L10 12.414l-1.793 1.793a1 1 0 01-1.414-1.414L8.586 11l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            <span class="font-bold">Educational guidance only.</span>
            <span>Not tax preparation or legal advice.</span>
        </div>

        <header class="space-y-2">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-gray-950">Library</h1>
            <p class="text-lg text-gray-600 max-w-3xl">Browse educational videos and documents. Unlock all content with a Pro subscription.</p>
        </header>

        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="relative flex-grow">
                    <input type="text" placeholder="Search content..." class="w-full px-4 py-3 pl-10 bg-gray-50 border border-gray-200 rounded-lg text-base focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1.5 bg-gray-100 border border-gray-200 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-200 cursor-pointer">Getting started & setup</span>
                    <span class="px-3 py-1.5 bg-gray-100 border border-gray-200 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-200 cursor-pointer">Income entry (W-2 + common forms)</span>
                    <span class="px-3 py-1.5 bg-gray-100 border border-gray-200 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-200 cursor-pointer">Gig economy (1099 + Schedule C)</span>
                    <span class="px-3 py-1.5 bg-gray-100 border border-gray-200 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-200 cursor-pointer">Credits & deductions...</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 border-b border-gray-100 pb-3">
                <button class="flex items-center space-x-2 px-5 py-2.5 rounded-full bg-blue-50 text-blue-800 border-2 border-blue-100 font-semibold text-lg hover:bg-blue-100 transition shadow-sm">
                    <svg class="h-6 w-6 text-brand-red" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M10 15L15 12L10 9V15Z"></path>
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20Z"></path>
                    </svg>
                    <span>Videos</span> <span class="text-blue-600 font-normal ml-1">(5)</span>
                </button>
                <button class="flex items-center space-x-2 px-5 py-2.5 rounded-full bg-white text-gray-700 border border-gray-200 font-medium text-lg hover:bg-gray-50 transition shadow-sm">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Docs</span> <span class="text-gray-500 font-normal ml-1">(10)</span>
                </button>
                <button class="flex items-center space-x-2 px-5 py-2.5 rounded-full bg-white text-gray-700 border border-gray-200 font-medium text-lg hover:bg-gray-50 transition shadow-sm">
                    <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Packs</span> <span class="text-gray-500 font-normal ml-1">(2)</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-x-8 gap-y-12">

            <article class="bg-white rounded-3xl border border-gray-100 shadow-lg hover:shadow-2xl transition-shadow duration-300 overflow-hidden group">
                <div class="aspect-video bg-gray-100 relative flex items-center justify-center cursor-pointer">
                    <div class="w-16 h-16 rounded-full bg-brand-red flex items-center justify-center transition-transform group-hover:scale-110 shadow-xl border-4 border-white/80">
                        <svg class="h-10 w-10 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10 16.5L16.5 12L10 7.5V16.5Z"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-6 md:p-8 space-y-4">
                    <h2 class="text-xl md:text-2xl font-bold leading-tight text-gray-950 group-hover:text-blue-700 transition">What's New in 2026 (Part 2) - TurboTax Tax Tips Podcast</h2>
                    <div class="flex items-center justify-between text-base text-gray-600">
                        <span class="px-3 py-1 bg-gray-100 rounded-full border border-gray-200 text-sm font-medium">Getting started & setup</span>
                        <span class="font-medium">2 min</span>
                    </div>
                </div>
            </article>

            <article class="bg-white rounded-3xl border border-gray-100 shadow-lg hover:shadow-2xl transition-shadow duration-300 overflow-hidden group">
                <div class="aspect-video bg-gray-100 relative flex items-center justify-center cursor-pointer">
                    <div class="w-16 h-16 rounded-full bg-brand-red flex items-center justify-center transition-transform group-hover:scale-110 shadow-xl border-4 border-white/80">
                        <svg class="h-10 w-10 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10 16.5L16.5 12L10 7.5V16.5Z"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-6 md:p-8 space-y-4">
                    <h2 class="text-xl md:text-2xl font-bold leading-tight text-gray-950 group-hover:text-blue-700 transition">Estimated Tax Payments</h2>
                    <div class="flex items-center justify-between text-base text-gray-600">
                        <span class="px-3 py-1 bg-gray-100 rounded-full border border-gray-200 text-sm font-medium">Getting started & setup</span>
                        <span class="font-medium">2 min</span>
                    </div>
                </div>
            </article>

            <article class="bg-white rounded-3xl border border-gray-100 shadow-sm opacity-60">
                <div class="aspect-video bg-gray-100 relative flex items-center justify-center">
                    <div class="w-16 h-16 rounded-full bg-brand-red flex items-center justify-center opacity-40">
                         <svg class="h-10 w-10 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10 16.5L16.5 12L10 7.5V16.5Z"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-6 md:p-8 space-y-4">
                    <h2 class="text-xl md:text-2xl font-bold leading-tight text-gray-950">[Upcoming Title...]</h2>
                </div>
            </article>

             <article class="bg-white rounded-3xl border border-gray-100 shadow-sm opacity-60">
                <div class="aspect-video bg-gray-100 relative flex items-center justify-center">
                    <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center">
                         <svg class="h-10 w-10 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                         </svg>
                    </div>
                </div>
                <div class="p-6 md:p-8 space-y-4">
                    <h2 class="text-xl md:text-2xl font-bold leading-tight text-gray-950">IRS Releases NEW [Partial Text]</h2>
                    <div class="text-base text-gray-600">
                        <span>General</span>
                    </div>
                </div>
            </article>

        </div>

    </div>

</x-app-client>