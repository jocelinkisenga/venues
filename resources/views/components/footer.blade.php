<footer class="bg-white dark:bg-black border-t border-slate-200 dark:border-white/10 pt-16 pb-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-16">
            <div class="space-y-6 lg:col-span-2">
                <a href="/" class="flex items-center gap-2">
                    <span class="text-xl font-bold tracking-tighter uppercase text-slate-900 dark:text-white">
                        UNSTACK <span class="text-blue-600 dark:text-blue-500">MY TAXES</span>
                    </span>
                </a>
                <p class="text-slate-500 dark:text-gray-400 text-sm leading-relaxed max-w-sm">
                    Smart tax optimization for the Gig Economy and independent professionals. Secure, fast, and precise.
                </p>

                <!-- Newsletter Subscription Form -->
               @livewire("news-letter")

                <div class="flex space-x-4 pt-2">
                    <a href="#" class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-500 transition">
                        <span class="sr-only">LinkedIn</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                    <a href="#" class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-500 transition">
                        <span class="sr-only">Twitter (X)</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-900 dark:text-white mb-6">Platform</h3>
                <ul class="space-y-4">
                    <li><a href="/" class="text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-500 transition">Free Simulation</a></li>
                    <li><a href="/login" class="text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-500 transition">Client Portal</a></li>
                    <li><a href="/register" class="text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-500 transition">Create Account</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-900 dark:text-white mb-6">Company</h3>
                <ul class="space-y-4">
                    <li><a href="/services" class="text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-500 transition">Our Services</a></li>
                    <li><a href="/about" class="text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-500 transition">About Us</a></li>
                    <li><a href="/contact" class="text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-500 transition">Contact</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-900 dark:text-white mb-6">Legal</h3>
                <ul class="space-y-4">
                    <li><a href="{{ route('terms') }}" class="text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-500 transition">Terms of Service</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-500 transition">Privacy Policy</a></li>
                    <li><a href="#" class="text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-500 transition">Cookie Settings</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-100 dark:border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs text-slate-400 uppercase tracking-widest">
                © 2026 ILANDS SOLUTIONS. All Rights Reserved.
            </p>
            <div class="flex items-center gap-2 text-xs text-slate-400 uppercase tracking-widest">
                <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                Systems Operational
            </div>
        </div>
    </div>
</footer>