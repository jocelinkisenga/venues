@php
use App\Enums\SubscriptionPlan;
@endphp
<x-app-client>
<!-- Content -->


  <main class="mx-auto w-full max-w-5xl px-4 py-6 sm:px-6 sm:py-10">
    <div>
      <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">Welcome back!</h1>
      <p class="mt-1 text-sm text-muted sm:text-base">Continue your tax education journey.</p>
    </div>

    <!-- Upgrade banner -->
    @if(auth()->user()->plan != SubscriptionPlan::PREMIUM)
    <section class="mt-6 overflow-hidden rounded-2xl p-5 text-white sm:p-6" style="background:linear-gradient(135deg,#1e3a5f,#33527f)" aria-labelledby="upgrade-title">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-4">
          <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-white/15"><svg class="h-6 w-6"><use href="#i-crown"/></svg></span>
          <div>
            <h2 id="upgrade-title" class="text-lg font-semibold">Unlock full access</h2>
            <p class="mt-0.5 text-sm text-white/80">Get unlimited videos, documents, and AI chat for just $9/month.</p>
          </div>
        </div>
        <a href="#" class="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-5 text-sm font-semibold text-[#1e3a5f] transition hover:bg-white/90 active:scale-[.98] sm:h-10">
          Upgrade now <svg class="h-4 w-4"><use href="#i-arrow"/></svg>
        </a>
      </div>
    </section>
  @endif
    <!-- Start here -->

    <!-- Quick actions -->
    <section class="mt-10" aria-labelledby="quick-title">
      <h2 id="quick-title" class="text-lg font-semibold">Quick actions</h2>
      <div class="mt-4 grid gap-3 sm:grid-cols-3 sm:gap-4">
        <a href="{{route('library.index')}}" class="flex items-center gap-3 rounded-2xl border border-line bg-surface p-4 transition hover:border-navy/40 hover:shadow-md">
          <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-500/15"><svg class="h-5 w-5"><use href="#i-book"/></svg></span>
          <span class="min-w-0"><span class="block text-sm font-semibold">Browse library</span><span class="block truncate text-xs text-muted">Explore videos and documents</span></span>
        </a>
        <a href="/chat" class="flex items-center gap-3 rounded-2xl border border-line bg-surface p-4 transition hover:border-navy/40 hover:shadow-md">
          <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-green-100 text-green-600 dark:bg-green-500/15"><svg class="h-5 w-5"><use href="#i-chat"/></svg></span>
          <span class="min-w-0"><span class="block text-sm font-semibold">Ask AI</span><span class="block truncate text-xs text-muted">Get instant tax answers</span></span>
        </a>
        <a href="/subscription" class="flex items-center gap-3 rounded-2xl border border-line bg-surface p-4 transition hover:border-navy/40 hover:shadow-md">
          <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-500/15"><svg class="h-5 w-5"><use href="#i-users"/></svg></span>
          <span class="min-w-0"><span class="block text-sm font-semibold">Manage subscription</span><span class="block truncate text-xs text-muted">Connect with a professional</span></span>
        </a>
      </div>
    </section>

{{-- begin contents --}}
      <section class="mt-10" aria-labelledby="start-title">
      <h2 id="start-title" class="text-lg font-semibold">🎯 Start here</h2>
      <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($contents as $content)

        <a href="{{ route('library.show', $content->slug) }}" class="group flex flex-col rounded-2xl border border-line bg-surface p-5 transition hover:border-navy/40 hover:shadow-md">
          <div class="flex items-center gap-3">
            @if($content->type == "document")
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-500/15"><svg class="h-5 w-5"><use href="#i-file"/></svg></span>
            <span class="text-xs font-medium text-muted">Checklist</span>
            @elseif($content->type == "video")
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-red-100 text-red-600 dark:bg-red-500/15"><svg class="h-5 w-5"><use href="#i-play"/></svg></span>
            @else
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-teal-100 text-teal-600 dark:bg-teal-500/15"><svg class="h-5 w-5"><use href="#i-sparkles"/></svg></span>
            @endif
          </div>
          <h3 class="mt-4 text-sm font-semibold leading-snug">Profit or loss from business (sole proprietorship)</h3>
          <p class="mt-auto pt-4 text-xs text-muted">{{$content->type}}</p>
        </a>
        @endforeach

      </div>
    </section>
{{-- endcontents --}}

    <!-- Usage -->

  </main>

</x-app-client>