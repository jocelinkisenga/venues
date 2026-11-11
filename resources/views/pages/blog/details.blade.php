<x-guest-layout>
    <div class="bg-white text-gray-900 dark:bg-black dark:text-white min-h-screen py-16">
        <article class="max-w-4xl mx-auto px-6">
            
            <!-- Header de l'article -->
            <header class="mb-10 text-center">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <span class="px-3 py-1 bg-green-500/20 text-green-500 rounded-full text-xs font-semibold uppercase tracking-wider">
                        {{ $article->category }}
                    </span>
                    <span class="text-gray-500 text-sm italic">{{ $article->created_at->format('M d, Y') }}</span>
                    <span class="text-gray-500 text-sm">• {{ $article->read_time }} read</span>
                </div>
                
                <h1 class="text-3xl md:text-5xl font-extrabold mb-6 leading-tight">
                    {{ $article->title }}
                </h1>
                
                <p class="text-xl text-gray-400 max-w-2xl mx-auto italic">
                    {{ $article->excerpt }}
                </p>
            </header>

            <!-- Image mise en avant -->
            <div class="rounded-3xl overflow-hidden border border-green-500/20 mb-12 shadow-2xl shadow-green-900/10">
                <img src="{{ asset('storage/' . $article->thumbnail) }}" 
                     alt="{{ $article->title }}" 
                     class="w-full h-auto object-cover">
            </div>

            <!-- Contenu principal -->
            <div class="prose prose-invert prose-lg max-w-none prose-green">
                {!! $article->content !!}
            </div>

            <!-- Footer de l'article / Auteur -->
            <section class="mt-20 pt-10 border-t border-green-500/10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-green-600 to-black flex items-center justify-center font-bold text-green-500">
                        IL
                    </div>
                    <div>
                        <h4 class="font-bold">ILANDS Expert Review</h4>
                        <p class="text-sm text-gray-500">Verified Tax Compliance Specialist</p>
                    </div>
                </div>
            </section>
        </article>
    </div>
</x-guest-layout>