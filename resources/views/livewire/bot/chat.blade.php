<div class="fixed bottom-4 right-4 z-50 sm:bottom-6 sm:right-6 lg:bottom-8 lg:right-8"
     x-data="{
         open: false,
         wide: false,
         showSuggestions: true,
         autoFocusing: false,
         scrollToBottom(smooth = true) {
             const el = this.$refs.messages;
             if (el) el.scrollTo({ top: el.scrollHeight, behavior: smooth ? 'smooth' : 'auto' });
         }
     }"
     x-init="$watch('open', value => {
         if (value) $nextTick(() => {
             scrollToBottom(false);
             if (window.innerWidth >= 640) { autoFocusing = true; $refs.input?.focus(); autoFocusing = false; }
         });
     })"
     @keydown.escape.window="open = false"
     @open-chat.window="open = true"
     >

    <!-- Chat window -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         role="dialog" aria-label="Unstack my taxes - Assistant"
         :class="wide
            ? 'sm:w-[420px] sm:h-[600px] md:w-[600px] md:h-[calc(100vh-8rem)] lg:w-[760px] xl:w-[860px]'
            : 'sm:w-[420px] sm:h-[600px] md:w-[460px] md:h-[660px] lg:w-[520px] lg:h-[720px] xl:w-[560px] xl:h-[760px]'"
         class="fixed inset-x-3 top-3 bottom-24 flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl transition-[width,height] duration-300 dark:border-gray-800 dark:bg-gray-900
                sm:absolute sm:inset-auto sm:bottom-20 sm:right-0 sm:max-h-[calc(100vh-8rem)] sm:rounded-3xl sm:shadow-blue-900/20 sm:ring-1 sm:ring-black/5 md:bottom-24"
         style="display: none;">

        <!-- Header -->
        <div class="flex items-center justify-between bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3 text-white md:px-6 md:py-4">
            <div class="flex min-w-0 items-center gap-3 md:gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/15 ring-1 ring-white/25 md:h-12 md:w-12">
                    <svg class="h-6 w-6 md:h-7 md:w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m-5.25.75h.008v.008H9.75V9zm4.5 5.25h.008v.008h-.008v-.008zM6.75 3h10.5A2.25 2.25 0 0119.5 5.25v15.19a.56.56 0 01-.9.45L16.5 19.5l-2.1 1.39a.56.56 0 01-.62 0L12 19.7l-1.78 1.19a.56.56 0 01-.62 0L7.5 19.5l-2.1 1.39a.56.56 0 01-.9-.45V5.25A2.25 2.25 0 016.75 3z"/>
                    </svg>
                </div>
                <div class="min-w-0 leading-tight">
                    <p class="truncate text-sm font-semibold md:text-base lg:text-lg">Unstack my taxes</p>
                    <p class="mt-0.5 flex items-center gap-1.5 text-xs text-blue-100 md:text-sm">
                        <span class="relative flex h-2 w-2 md:h-2.5 md:w-2.5">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-300 opacity-75"></span>
                            <span class="relative inline-flex h-full w-full rounded-full bg-emerald-400"></span>
                        </span>
                        <span>Online</span>
                        <span class="truncate opacity-70">· {{ $theme }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-1">
                <!-- Expand / reduce (desktop only) -->
                <button type="button" @click="wide = !wide" :aria-label="wide ? 'Reduce chat' : 'Expand chat'" :title="wide ? 'Reduce' : 'Expand'"
                        class="hidden rounded-lg p-2 text-blue-100 transition hover:bg-white/15 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white/60 md:flex">
                    <svg x-show="!wide" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                    <svg x-show="wide" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25"/></svg>
                </button>

                <button type="button" @click="open = false" aria-label="Close chat"
                        class="rounded-lg p-1.5 text-blue-100 transition hover:bg-white/15 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white/60 md:p-2">
                    <svg class="h-5 w-5 md:h-6 md:w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Permanent notice -->
        <div class="flex items-center gap-2 border-b border-amber-200/70 bg-amber-50 px-4 py-1.5 text-xs font-medium text-amber-800 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300 md:px-6 md:py-2 md:text-sm">
            <svg class="h-4 w-4 shrink-0 md:h-5 md:w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.04-.02a.75.75 0 011.06.85l-.7 2.8a.75.75 0 001.07.85l.04-.02M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
            <span>Educational guidance only</span>
            <span class="hidden font-normal opacity-80 sm:inline">— not tax, legal or accounting advice</span>
        </div>

        <!-- Messages -->
        <div x-ref="messages"
             x-init="new MutationObserver(() => scrollToBottom()).observe($el, { childList: true, subtree: true, attributes: true, attributeFilter: ['style'] })"
             class="flex-1 space-y-3 overflow-y-auto scroll-smooth bg-gray-50 px-4 py-4 text-sm text-gray-700 dark:bg-black/20 dark:text-gray-300 md:space-y-5 md:px-6 md:py-6 md:text-[15px] lg:text-base">

            @foreach($messages as $msg)
                @php $isUser = $msg['sender'] === 'user'; @endphp
                <div wire:key="msg-{{ $loop->index }}" class="flex items-end gap-2 md:gap-3 {{ $isUser ? 'justify-end' : 'justify-start' }}">
                    @unless($isUser)
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white md:h-9 md:w-9">
                            <svg class="h-4 w-4 md:h-5 md:w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                        </div>
                    @endunless

                    {{-- Bubble: width cap on the bubble, typography on the inner element (no max-width conflict with "prose") --}}
                    <div class="min-w-0 max-w-[85%] overflow-x-auto break-words px-3.5 py-2.5 leading-relaxed md:max-w-[80%] md:px-5 md:py-3.5
                                {{ $isUser
                                    ? 'whitespace-pre-line rounded-2xl rounded-br-md bg-blue-600 text-white shadow-sm'
                                    : 'rounded-2xl rounded-bl-md border border-gray-200 bg-white text-gray-800 shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100' }}">
                        <div class="prose prose-sm max-w-none md:prose-base {{ $isUser ? 'prose-invert' : 'dark:prose-invert' }}
                                    [&>*:first-child]:mt-0 [&>*:last-child]:mb-0 prose-p:my-2 prose-li:my-0.5 prose-a:text-blue-600 dark:prose-a:text-blue-400
                                    prose-table:my-3 prose-table:text-xs md:prose-table:text-sm prose-th:px-3 prose-th:py-2 prose-th:text-left prose-th:bg-gray-100 dark:prose-th:bg-gray-700 prose-td:px-3 prose-td:py-2">{!! $this->markdown($msg['text']) !!}</div>
                    </div>
                </div>
            @endforeach

            <!-- "AI is typing..." indicator -->
            <div wire:loading.flex wire:target="sendMessage,selectQuestion" class="items-end gap-2 md:gap-3">
                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white md:h-9 md:w-9">
                    <svg class="h-4 w-4 md:h-5 md:w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                </div>
                <div class="flex items-center gap-1 rounded-2xl rounded-bl-md border border-gray-200 bg-white px-3.5 py-3 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:gap-1.5 md:px-5 md:py-4" aria-label="The assistant is writing a reply">
                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400 md:h-2 md:w-2" style="animation-delay: 0ms"></span>
                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400 md:h-2 md:w-2" style="animation-delay: 150ms"></span>
                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400 md:h-2 md:w-2" style="animation-delay: 300ms"></span>
                </div>
            </div>
        </div>

        <!-- Suggestions (hideable) -->
        @if(!empty($suggestedQuestions) && !$guestLimitReached)
            <div x-show="showSuggestions"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="border-t border-gray-200 bg-white px-3 pb-2 pt-2 dark:border-gray-800 dark:bg-gray-900 md:px-6 md:pb-3 md:pt-3">
                <div class="mb-1.5 flex items-center justify-between px-1 md:mb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 md:text-xs">Suggestions</span>
                    <button type="button" @click="showSuggestions = false"
                            title="Hide suggestions" aria-label="Hide suggestions"
                            class="rounded-md p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                        <svg class="h-4 w-4 md:h-5 md:w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex max-h-28 flex-wrap gap-1.5 overflow-y-auto md:max-h-36 md:gap-2">
                    @foreach($suggestedQuestions as $question)
                        <button type="button"
                                wire:key="suggestion-{{ $loop->index }}"
                                @click="showSuggestions = false"
                                wire:click="selectQuestion(@js($question))"
                                wire:loading.attr="disabled" wire:target="sendMessage,selectQuestion"
                                class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-left text-xs text-blue-700 transition hover:bg-blue-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 disabled:opacity-50 dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-300 dark:hover:bg-blue-500/20 md:px-4 md:py-2 md:text-sm">
                            {{ $question }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Input area / limit reached -->
        @if($guestLimitReached)
            <!-- Limit reached: invite to create an account -->
            <div class="border-t border-gray-200 bg-gradient-to-b from-blue-50/70 to-white p-5 text-center dark:border-gray-800 dark:from-blue-500/10 dark:to-gray-900 md:p-8">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg shadow-blue-600/30 md:mb-4 md:h-14 md:w-14">
                    <svg class="h-6 w-6 md:h-7 md:w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                </div>

                <p class="text-base font-semibold text-gray-900 dark:text-gray-100 md:text-lg">You have used all your free questions</p>
                <p class="mx-auto mt-1 max-w-xs text-xs leading-relaxed text-gray-500 dark:text-gray-400 md:max-w-sm md:text-sm">Create an account and continue asking questions.</p>

                <a href="{{ Route::has('register') ? route('register') : url('/register') }}"
                   class="group mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/30 transition hover:from-blue-500 hover:to-blue-400 hover:shadow-blue-500/40 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/40 md:mt-5 md:py-3.5 md:text-base">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                    <span>Create account</span>
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>

                <p class="mt-3 text-xs text-gray-500 dark:text-gray-400 md:mt-4 md:text-sm">
                    Already have an account?
                    <a href="{{ Route::has('login') ? route('login') : url('/login') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">Log in</a>
                </p>
            </div>
        @else
            <form wire:submit.prevent="sendMessage" class="border-t border-gray-200 bg-white p-3 dark:border-gray-800 dark:bg-gray-900 md:p-4 lg:px-6 lg:py-5">
                @error('newMessage')
                    <p class="mb-1.5 px-1 text-xs text-red-500 md:text-sm">{{ $message }}</p>
                @enderror

                <div class="flex items-center gap-2 md:gap-3">
                    @if(!empty($suggestedQuestions))
                        <!-- Show suggestions again -->
                        <button type="button" x-show="!showSuggestions" @click="showSuggestions = true"
                                title="Show suggestions" aria-label="Show suggestions"
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-gray-400 transition hover:bg-gray-100 hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:bg-gray-800 dark:hover:text-blue-300 md:h-12 md:w-12"
                                style="display: none;">
                            <svg class="h-5 w-5 md:h-6 md:w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>
                        </button>
                    @endif

                    <input x-ref="input" type="text" wire:model="newMessage"
                           @focus="if (!autoFocusing) showSuggestions = false" @input="showSuggestions = false" maxlength="1000" autocomplete="off"
                           placeholder="Ask your question..."
                           class="min-w-0 flex-1 rounded-full border border-gray-300 bg-gray-50 px-4 py-2.5 text-base text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-500 md:px-5 md:py-3">

                    <button type="submit" aria-label="Send"
                            wire:loading.attr="disabled" wire:target="sendMessage,selectQuestion"
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white transition hover:bg-blue-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 dark:focus-visible:ring-offset-gray-900 md:h-12 md:w-12">
                        <svg class="h-5 w-5 md:h-6 md:w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.27 3.13a.75.75 0 011.04-.89l16.5 8.25a.75.75 0 010 1.34l-16.5 8.25a.75.75 0 01-1.04-.89L6 12zm0 0h7.5"/></svg>
                    </button>
                </div>

                <p class="mt-2 hidden text-center text-xs text-gray-400 dark:text-gray-500 md:block">Press Enter to send · Esc to close</p>
            </form>
        @endif
    </div>

    <!-- Floating button -->
    <button type="button" @click="open = !open" :aria-expanded="open" aria-label="Open chat"
            class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-600 text-white shadow-2xl transition hover:scale-105 hover:bg-blue-500 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/50 md:h-16 md:w-16">
        <svg x-show="!open" class="h-6 w-6 md:h-7 md:w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
        <svg x-show="open" class="h-6 w-6 md:h-7 md:w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
    </button>
</div>