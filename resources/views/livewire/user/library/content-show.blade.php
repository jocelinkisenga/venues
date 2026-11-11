<div class="min-h-screen bg-gray-50 dark:bg-black">

    <div class="max-w-7xl mx-auto px-6 py-10">

        <div class="grid lg:grid-cols-12 gap-10">

            <!-- MAIN CONTENT -->

            <div class="lg:col-span-8">

                <!-- THUMBNAIL -->

                @if($content->thumbnail)

                    <div class="overflow-hidden rounded-3xl mb-8 border border-gray-200 dark:border-gray-800">

                        <img
                            src="{{ asset('storage/' . $content->thumbnail) }}"
                            class="w-full h-[420px] object-cover"
                        >

                    </div>

                @endif

                <!-- META -->

                <div class="flex flex-wrap items-center gap-3 mb-6">

                    <!-- TYPE -->

                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-500/10 text-blue-500">

                        {{ strtoupper($content->type) }}

                    </span>

                    <!-- ACCESS -->

                    @if($content->access_level === 'free')

                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-500/10 text-blue-500">

                            FREE

                        </span>

                    @elseif($content->access_level === 'pro')

                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-500">

                            PRO

                        </span>

                    @else

                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-500/10 text-purple-500">

                            PREMIUM

                        </span>

                    @endif

                    <!-- DATE -->

                    <span class="text-sm text-gray-500 dark:text-gray-400">

                        {{ $content->created_at->format('M d, Y') }}

                    </span>

                </div>

                <!-- TITLE -->

                <h1 class="text-4xl md:text-5xl font-bold leading-tight text-gray-900 dark:text-white">

                    {{ $content->title }}

                </h1>

                <!-- EXCERPT -->

                <p class="mt-6 text-lg leading-relaxed text-gray-500 dark:text-gray-400">

                    {{ $content->excerpt }}

                </p>

                <!-- AUTHOR -->

                <div class="mt-8 flex items-center gap-4 p-5 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900">

                    <img
                        src="https://ui-avatars.com/api/?name=Shabani"
                        class="w-14 h-14 rounded-full"
                    >

                    <div>

                        <h3 class="font-semibold text-gray-900 dark:text-white">

                            Shabani EA

                        </h3>

                        <p class="text-sm text-gray-500 dark:text-gray-400">

                            Licensed Enrolled Agent & Founder

                        </p>

                    </div>

                </div>

                <!-- VIDEO -->

                @if($content->type === 'video' && $content->video_url)

                    <div class="mt-10 overflow-hidden rounded-3xl border border-gray-200 dark:border-gray-800" oncontextmenu="return false;">

                        <iframe controlsList="nodownload" sandbox="allow-scripts allow-same-origin allow-presentation"
                            src="{{ asset('storage/' . $content->video_url) }}"
                            class="w-full h-[500px]"
                            allowfullscreen
                        ></iframe>

                    </div>

                @endif

                <!-- PDF DOWNLOAD -->

                @if($content->document_path)

                    <div class="mt-10">

                        <a
                            href="{{ asset('storage/' . $content->document_path) }}"
                            target="_blank"
                            class="inline-flex items-center px-6 py-4 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-medium transition"
                        >
                            Download PDF
                        </a>

                    </div>

                @endif

                <!-- ARTICLE -->

                <div class="mt-14">

                    <article class="prose prose-lg text-gray-700 dark:text-gray-300  dark:prose-invert max-w-none">

                        {!! $content->content !!}

                    </article>

                </div>

                <!-- SHARE -->

                <div class="mt-14 flex flex-wrap gap-4">

                    <button class="px-5 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:border-blue-500 transition">

                        Share

                    </button>

                    <button
    wire:click="toggleSave"
    class="px-5 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:border-blue-500 transition"
>

    @if($isSaved)

        ❤️ Saved

    @else

        🤍 Save

    @endif

</button>

                </div>

            </div>

            <!-- SIDEBAR -->

            <div class="lg:col-span-4">

                <div class="sticky top-24 space-y-8">

                    <!-- CONTENT INFO -->

                    <div class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">

                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">

                            Content Info

                        </h3>

                        <div class="space-y-5">

                            <div>

                                <p class="text-sm text-gray-500 dark:text-gray-400">

                                    Type

                                </p>

                                <p class="font-medium text-gray-900 dark:text-white">

                                    {{ ucfirst($content->type) }}

                                </p>

                            </div>

                            <div>

                                <p class="text-sm text-gray-500 dark:text-gray-400">

                                    Access

                                </p>

                                <p class="font-medium text-gray-900 dark:text-white">

                                    {{ ucfirst($content->access_level) }}

                                </p>

                            </div>

                            <div>

                                <p class="text-sm text-gray-500 dark:text-gray-400">

                                    Published

                                </p>

                                <p class="font-medium text-gray-900 dark:text-white">

                                    {{ $content->created_at->diffForHumans() }}

                                </p>

                            </div>

                        </div>

                    </div>

                    <!-- RELATED CONTENT -->

                    @if($relatedContents->count())

                        <div class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">

                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">

                                Related Content

                            </h3>

                            <div class="space-y-6">

                                @foreach($relatedContents as $related)

                                    <a
                                        href="{{ route('library.show', $related->slug) }}"
                                        class="flex gap-4 group"
                                    >

                                        @if($related->thumbnail)

                                            <img
                                                src="{{ asset('storage/' . $related->thumbnail) }}"
                                                class="w-24 h-24 rounded-2xl object-cover"
                                            >

                                        @endif

                                        <div>

                                            <h4 class="font-medium text-gray-900 dark:text-white group-hover:text-blue-500 transition line-clamp-2">

                                                {{ $related->title }}

                                            </h4>

                                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">

                                                {{ $related->excerpt }}

                                            </p>

                                        </div>

                                    </a>

                                @endforeach

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>