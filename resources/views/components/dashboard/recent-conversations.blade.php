@php
$chats = App\Models\Chat::where('user_id', auth()->user()->id)->latest()->limit(5)->get();

@endphp
<section class="mt-10">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">
                Recent Conversations
            </h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Continue where you left off with ILANDS AI.
            </p>
        </div>

        <a href="{{route("hystory")}}" 
            class="hidden md:flex items-center gap-2 text-sm font-medium text-slate-600 transition hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
            View All
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
           @if(count($chats) === 0)
   <div class="p-8 text-center">
    <div class="text-sm text-gray-500 dark:text-gray-400">
     no conversation find .
    </div>
   </div>
   @else
        @foreach($chats as $chat)
        <div
            class="group rounded-2xl border border-slate-200 bg-white p-5 transition-all hover:-translate-y-1 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">

            <div class="flex items-start justify-between">

                <div class="flex gap-4">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                        🤖
                    </div>

                    <div>
                        <h4
                            class="font-semibold text-slate-900 dark:text-white">
                            {{ $chat->title }}
                        </h4>
                    </div>
                </div>

                <button
                    class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-white">
                    ⋮
                </button>

            </div>

            <div
                class="mt-5 flex flex-wrap items-center gap-3 text-xs text-slate-500 dark:text-slate-400">

                <span
                    class="rounded-full bg-slate-100 px-3 py-1 dark:bg-slate-800">
                    18 Messages
                </span>

                <span>#{{ $chat->id }} • {{ $chat->updated_at->diffForHumans() }}</span>

            </div>

            <div class="mt-5">
                <a href="{{ route('chat', ['chatId' => $chat->id]) }}" 
                    class="flex items-center gap-2 text-sm font-semibold text-indigo-600 transition hover:text-indigo-700 dark:text-indigo-400">
                    Continue Conversation
                    <svg class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

        </div>
        @endforeach
    @endif
    </div>

    {{-- Mobile View All --}}
    <div class="mt-5 md:hidden">
        <button
            class="w-full rounded-xl border border-slate-200 bg-white py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">
            View All Conversations
        </button>
    </div>
</section>