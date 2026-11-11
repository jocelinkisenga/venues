<div class="min-h-screen bg-gray-50 dark:bg-black">

    <div class="max-w-7xl mx-auto px-6 py-10">

        <div class="mb-10">

            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">

                Saved Content

            </h1>

            <p class="mt-3 text-gray-500 dark:text-gray-400">

                Your bookmarked resources.

            </p>

        </div>

        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">

            @forelse($contents as $content)

                <a
                    href="{{ route('library.show', $content->slug) }}"
                    class="group rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:shadow-xl transition"
                >

                    @if($content->thumbnail)

                        <img
                            src="{{ asset('storage/' . $content->thumbnail) }}"
                            class="w-full h-52 object-cover group-hover:scale-105 transition duration-500"
                        >

                    @endif

                    <div class="p-6">

                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">

                            {{ $content->title }}

                        </h2>

                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400 line-clamp-3">

                            {{ $content->excerpt }}

                        </p>

                    </div>

                </a>

            @empty

                <div class="col-span-full">

                    <div class="rounded-3xl border border-dashed border-gray-300 dark:border-gray-700 p-16 text-center">

                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">

                            No saved content yet

                        </h3>

                    </div>

                </div>

            @endforelse

        </div>

    </div>

</div>