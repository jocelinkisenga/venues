<div>
<div class="bg-black text-white min-h-screen">
    <section class="relative py-20 overflow-hidden border-b border-green-500/10">
        <div class="absolute inset-0 bg-gradient-to-br from-green-600/10 via-black to-black"></div>
        
        <div class="relative max-w-5xl mx-auto px-6 text-center">
            <span class="text-green-500 font-mono tracking-widest uppercase text-sm">Expert Insights</span>
            <h1 class="text-4xl md:text-6xl font-extrabold mt-4">
                ILANDS <span class="text-green-500">Tax Intelligence</span> Blog
            </h1>
            <p class="mt-6 text-gray-400 text-lg max-w-2xl mx-auto">
                Strategies and compliance guides for entrepreneurs and expats, powered by AI and verified by licensed professionals.
            </p>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($articles as $article)
                <article class="group bg-white/5 backdrop-blur-xl border border-green-500/10 rounded-2xl overflow-hidden hover:border-green-500/40 transition-all duration-300">
                    <div class="h-48 bg-gradient-to-tr from-green-900/40 to-black flex items-center justify-center border-b border-green-500/10">
                         <span class="text-green-500/30 font-bold text-4xl italic">ILANDS AI</span>
                    </div>

                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-green-500/20 text-green-500 rounded-full text-xs font-semibold">
                                {{ $article['category'] }}
                            </span>
                            <span class="text-gray-500 text-xs">{{ $article['read_time'] }} read</span>
                        </div>

                        <h2 class="text-xl font-bold group-hover:text-green-400 transition-colors mb-4">
                            <a href="/blog/{{ $article['slug'] }}">{{ $article['title'] }}</a>
                        </h2>

                        <p class="text-gray-400 text-sm leading-relaxed mb-6">
                            {{ $article['excerpt'] }}
                        </p>

                        <div class="flex items-center justify-between border-t border-white/5 pt-6">
                            <span class="text-xs text-gray-500 italic">By ILANDS Expert Review</span>
                            <a href="/blog/{{ $article['slug'] }}" class="text-green-500 font-semibold text-sm flex items-center gap-2 hover:gap-3 transition-all">
                                Read More 
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-20">
        <div class="max-w-4xl mx-auto px-6">
            <div class="bg-gradient-to-r from-green-900/20 to-black border border-green-500/30 rounded-3xl p-10 text-center">
                <h2 class="text-2xl font-bold mb-4">Subscribe to Tax Alerts</h2>
                <p class="text-gray-400 mb-8 italic">Stay updated with AI-driven tax optimization strategies.</p>
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <input type="email" placeholder="Enter your email" class="bg-white/5 border border-green-500/20 rounded-xl px-6 py-3 focus:border-green-500 outline-none w-full md:w-64">
                    <button class="bg-green-600 hover:bg-green-500 px-8 py-3 rounded-xl font-bold transition">Join List</button>
                </div>
            </div>
        </div>
    </section>
</div>
</div>
