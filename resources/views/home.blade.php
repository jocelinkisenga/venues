<x-guest-layout>

@section('title', 'Unstack My Taxes — Tax advisory for gig workers & expats')

@php
    $rotating = ['gig workers', 'freelancers', 'Uber & DoorDash drivers', 'American expats'];

    $tags = ['Mileage', 'Home office', 'Quarterly estimates', '1099-NEC', 'FEIE', 'FBAR', 'Foreign tax credit', 'Self-employment tax', 'Schedule C', 'Retirement contributions'];

    $benefits = [
        ['icon' => 'M12 3v18M17 7H9.5a3 3 0 000 6h5a3 3 0 010 6H6', 'title' => 'Save money', 'text' => 'Advisory from $9 and memberships from $19/month, instead of $300–$1,500 at a preparer.'],
        ['icon' => 'M12 7v5l3 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Save time', 'text' => 'A 2-minute screener, a 10–15 minute questionnaire, and a report in your inbox.'],
        ['icon' => 'M12 3l7 3v5c0 5-3 8-7 10-4-2-7-5-7-10V6l7-3zM9 12l2 2 4-4', 'title' => 'Expert guidance', 'text' => 'Built on the knowledge of a licensed Enrolled Agent, delivered at scale.'],
    ];

    $steps = [
        ['Take the screener', 'Six quick questions. We recommend the right service.'],
        ['Complete your profile', 'A secure 10–15 minute questionnaire about income and expenses.'],
        ['Get your report', 'A branded PDF with your personalized deduction strategy.'],
        ['Keep learning', 'Video lessons, templates and an assistant in your member portal.'],
    ];

    $plans = [
        ['name' => 'Basic', 'm' => 19, 'y' => 199, 'features' => ['50+ video lessons', 'Downloadable templates', 'AI assistant, 10 questions/month', 'Tax calendar & reminders']],
        ['name' => 'Plus', 'm' => 39, 'y' => 399, 'hot' => true, 'features' => ['Everything in Basic', 'Unlimited AI assistant', 'Quarterly tax planning checklist', 'Email support, 48-hour reply']],
        ['name' => 'Premium', 'm' => 79, 'y' => 799, 'features' => ['Everything in Plus', 'One 30-min video consultation/year', 'Priority support, 24-hour reply', 'Personalized tax strategy document']],
    ];

    $faqs = [
        ['Are you a tax preparer?', 'No. We provide tax education and advisory only. We don’t prepare or file returns.'],
        ['Who is behind the guidance?', 'A licensed Enrolled Agent, authorized by the U.S. Treasury to represent taxpayers before the IRS.'],
        ['Is my data secure?', 'Sensitive details are encrypted, and we collect only what we need to build your report.'],
        ['What is your refund policy?', 'If your report doesn’t meet expectations, contact us and we’ll make it right. Full terms are on our refund page.'],
    ];
@endphp

@push('styles')
<style>
    html { scroll-behavior: smooth; }
    .rv { opacity: 0; transform: translateY(26px); transition: opacity .8s cubic-bezier(.2,.7,.2,1), transform .8s cubic-bezier(.2,.7,.2,1); }
    .rv.in { opacity: 1; transform: none; }
    .bar { transform-origin: left; transform: scaleX(0); transition: transform 1.4s cubic-bezier(.2,.7,.2,1) .3s; }
    .in .bar { transform: scaleX(1); }
    details > summary { list-style: none; }
    details > summary::-webkit-details-marker { display: none; }
    details[open] .pl { transform: rotate(45deg); }
    .card { background: #fff; border: 1px solid #e3e9f4; }
    .dark .card { background: rgba(255,255,255,.04); border-color: rgba(255,255,255,.09); }
    @media (prefers-reduced-motion: reduce) {
        * { animation: none !important; transition: none !important; }
        .rv { opacity: 1; transform: none; }
        .bar { transform: none; }
    }
</style>
@endpush

@section('content')
<div class="font-sans text-slate-600 dark:text-slate-300 antialiased overflow-x-hidden bg-[#f5f8fe] dark:bg-[#071022]">

    {{-- HERO --}}
    <section class="relative pt-24 pb-16 md:pt-16 md:pb-24 px-5 overflow-hidden">
        <div class="absolute -top-24 -right-24 w-[28rem] h-[28rem] rounded-full bg-brand-500/20 dark:bg-brand-500/15 blur-3xl"></div>
        <div class="relative mx-auto max-w-6xl grid lg:grid-cols-[1.1fr_1fr] gap-12 items-center">
            <div>
     {{--            <p class="rv inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-400 border border-brand-100 dark:border-brand-500/20">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v5c0 5-3 8-7 10-4-2-7-5-7-10V6l7-3z"/><path d="M9 12l2 2 4-4"/></svg>
                    Guided by a licensed Enrolled Agent
                </p> --}}
                <h1 class="rv mt-6 text-[2.5rem] leading-[1.06] sm:text-6xl font-bold tracking-tight text-[#0b1530] dark:text-white" style="transition-delay:.08s">
                    Maximize your tax deductions <span class="font-serif italic font-medium text-brand-600 dark:text-brand-400">without</span> expensive CPAs.
                </h1>
                <p class="rv mt-5 text-lg sm:text-xl text-slate-600 dark:text-slate-300 max-w-xl" style="transition-delay:.16s">
                    Tax advisory for
                    <span class="relative inline-grid h-[1.6em] overflow-hidden align-bottom font-semibold text-[#0b1530] dark:text-white">
                        @foreach ($rotating as $i => $word)
                            <span class="col-start-1 row-start-1 opacity-0 animate-wd" style="animation-delay: {{ $i * 3 }}s">{{ $word }}</span>
                        @endforeach
                    </span>. From $9.
                </p>
                <div class="rv mt-8 flex flex-col sm:flex-row gap-3" style="transition-delay:.24s">
                    <a href="{{ url('/tax-screener') }}" class="group h-12 px-7 inline-flex items-center justify-center gap-2 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-semibold shadow-lg shadow-brand-600/25 transition">
                        Get your free tax screener
                        <svg class="transition group-hover:translate-x-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                    <a href="#pricing" class="h-12 px-7 inline-flex items-center justify-center rounded-full border border-slate-300 dark:border-white/15 font-semibold hover:bg-white dark:hover:bg-white/5 transition">See pricing</a>
                </div>
                <p class="rv mt-4 text-sm text-slate-500" style="transition-delay:.3s">Takes 2–3 minutes. Advisory and education only, not tax preparation.</p>
            </div>

            {{-- Illustration --}}
            <div class="rv relative mx-auto w-full max-w-md" style="transition-delay:.2s">
                <div class="card rounded-3xl p-5 shadow-2xl shadow-brand-600/10">
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                        <span>Your deduction snapshot</span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-400">Tax year 2026</span>
                    </div>
                    <p class="mt-3 text-4xl font-bold text-[#0b1530] dark:text-white">$<span data-count="4280">0</span> <span class="text-sm font-medium text-slate-500">possible deductions</span></p>
                    <svg viewBox="0 0 360 120" class="mt-4 w-full" fill="none">
                        <rect width="360" height="120" rx="16" class="fill-brand-50 dark:fill-brand-500/10"/>
                        <path d="M0 98h360" stroke="#94a3b8" stroke-opacity=".5" stroke-width="2"/>
                        <path d="M-20 98h400" stroke="#2f6bff" stroke-width="3" stroke-dasharray="26 26" class="animate-dash"/>
                        <g class="animate-bob">
                            <path d="M120 86v-14l14-14h58l18 14h14v14z" class="fill-brand-600"/>
                            <path d="M137 62h26v10h-36zM170 62h18l12 10h-30z" fill="#fff" fill-opacity=".85"/>
                            <circle cx="148" cy="88" r="9" fill="#0b1530"/><circle cx="148" cy="88" r="3.5" fill="#cbd5e1"/>
                            <circle cx="206" cy="88" r="9" fill="#0b1530"/><circle cx="206" cy="88" r="3.5" fill="#cbd5e1"/>
                        </g>
                        <g transform="translate(300 24)"><circle r="16" class="fill-white dark:fill-slate-800"/><circle r="9" stroke="#2f6bff" stroke-width="1.6"/><ellipse rx="4" ry="9" stroke="#2f6bff" stroke-width="1.4"/><path d="M-9 0h18" stroke="#2f6bff" stroke-width="1.4"/></g>
                        <path d="M40 40q100-50 250-14" stroke="#2f6bff" stroke-opacity=".5" stroke-dasharray="4 6"/>
                        <circle r="4" fill="#2f6bff"><animateMotion dur="5s" repeatCount="indefinite" path="M40 40q100-50 250-14"/></circle>
                    </svg>
                    <div class="mt-4 space-y-2.5 text-sm">
                        @foreach ([['Vehicle mileage', '$1,940', '78%', 'bg-brand-600'], ['Home office', '$1,180', '52%', 'bg-brand-500'], ['Phone & internet', '$1,160', '45%', 'bg-brand-400']] as $row)
                            <div>
                                <div class="flex justify-between"><span>{{ $row[0] }}</span><b class="text-[#0b1530] dark:text-white">{{ $row[1] }}</b></div>
                                <div class="mt-1 h-2 rounded-full bg-slate-100 dark:bg-white/10"><div class="bar h-2 rounded-full {{ $row[3] }}" style="width: {{ $row[2] }}"></div></div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="absolute -left-3 -top-4 sm:-left-8 animate-fl card rounded-2xl px-3.5 py-2.5 text-xs font-semibold shadow-lg flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-brand-600 grid place-items-center text-white">✓</span>An example
                </div>
                <div class="absolute -right-2 -bottom-5 sm:-right-6 animate-fl card rounded-2xl px-3.5 py-2.5 text-xs font-semibold shadow-lg" style="animation-delay:-3s">FEIE · FBAR · FATCA explained</div>
            </div>
        </div>
    </section>

    {{-- MARQUEE --}}
    <section class="py-5 border-y border-slate-200 dark:border-white/10 overflow-hidden bg-white/60 dark:bg-white/[.02]">
        <div class="flex w-max animate-mq text-sm font-semibold text-slate-400 dark:text-slate-500 whitespace-nowrap">
            @for ($r = 0; $r < 2; $r++)
                <div class="flex gap-10 pr-10">
                    @foreach ($tags as $tag)<span>◆ {{ $tag }}</span>@endforeach
                </div>
            @endfor
        </div>
    </section>

    {{-- PERSONAS --}}
    <section class="py-24 px-5">
        <div class="mx-auto max-w-6xl">
            <h2 class="rv text-3xl sm:text-5xl font-bold tracking-tight text-[#0b1530] dark:text-white max-w-2xl">Made for people the big tax brands overlook.</h2>
            <div class="mt-12 grid md:grid-cols-2 gap-5">
                <article class="rv card rounded-3xl p-8 hover:-translate-y-1 transition duration-300">
                    <svg viewBox="0 0 120 80" class="w-28" fill="none">
                        <path d="M10 62h100" stroke="#94a3b8" stroke-width="2"/>
                        <path d="M10 62h100" stroke="#2f6bff" stroke-width="2.5" stroke-dasharray="10 12" class="animate-dash"/>
                        <g class="animate-bob"><path d="M28 54V42l10-10h34l12 10h8v12z" class="fill-brand-600"/><circle cx="42" cy="55" r="6" fill="#0b1530"/><circle cx="82" cy="55" r="6" fill="#0b1530"/></g>
                        <path d="M92 8a10 10 0 0110 10c0 8-10 18-10 18S82 26 82 18a10 10 0 0110-10z" class="fill-emerald-500"/><circle cx="92" cy="18" r="3.5" fill="#fff"/>
                    </svg>
                    <h3 class="mt-5 text-2xl font-bold text-[#0b1530] dark:text-white">Gig workers &amp; freelancers</h3>
                    <p class="mt-2">Uber, DoorDash, creators and 1099 pros. Learn which expenses count, how to track mileage, and how quarterly taxes work.</p>
                    <ul class="mt-5 space-y-2 text-sm font-medium">
                        <li>✓ Mileage, phone, supplies and home office</li>
                        <li>✓ Simple record-keeping templates</li>
                        <li>✓ Plans from $19/month vs. $300+ at a preparer</li>
                    </ul>
                </article>
                <article class="rv card rounded-3xl p-8 hover:-translate-y-1 transition duration-300" style="transition-delay:.1s">
                    <svg viewBox="0 0 120 80" class="w-28" fill="none">
                        <circle cx="48" cy="42" r="30" class="fill-brand-50 dark:fill-brand-500/15" stroke="#2f6bff" stroke-width="2"/>
                        <ellipse cx="48" cy="42" rx="12" ry="30" stroke="#2f6bff" stroke-width="1.5"/>
                        <path d="M18 42h60M24 26h48M24 58h48" stroke="#2f6bff" stroke-width="1.5"/>
                        <path d="M60 14q40 0 52 30" stroke="#2f6bff" stroke-dasharray="4 5"/>
                        <g class="animate-fl"><path d="M104 40l8 6-10 2-4 8-3-2 2-8-8-3 3-3 9 2z" class="fill-brand-600"/></g>
                    </svg>
                    <h3 class="mt-5 text-2xl font-bold text-[#0b1530] dark:text-white">American expats</h3>
                    <p class="mt-2">Living abroad and unsure about FEIE, foreign tax credits, FBAR or FATCA? Get form-by-form guidance without the $800–$1,500 price tag.</p>
                    <ul class="mt-5 space-y-2 text-sm font-medium">
                        <li>✓ FEIE vs. foreign tax credit, explained</li>
                        <li>✓ Deadline reminders for international filers</li>
                        <li>✓ Remote-first, works across time zones</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    {{-- BENEFITS --}}
    <section class="py-8 pb-24 px-5">
        <div class="mx-auto max-w-6xl grid md:grid-cols-3 gap-5">
            @foreach ($benefits as $i => $b)
                <article class="rv card rounded-3xl p-7 hover:-translate-y-1 transition duration-300" style="transition-delay: {{ $i * .1 }}s">
                    <span class="grid place-items-center w-11 h-11 rounded-xl bg-brand-50 dark:bg-brand-500/15 text-brand-600 dark:text-brand-400">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $b['icon'] }}"/></svg>
                    </span>
                    <h3 class="mt-5 text-xl font-bold text-[#0b1530] dark:text-white">{{ $b['title'] }}</h3>
                    <p class="mt-2">{{ $b['text'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section id="how" class="py-24 px-5 bg-[#0b1530] text-white dark:bg-[#0a1630]">
        <div class="mx-auto max-w-6xl">
            <h2 class="rv text-3xl sm:text-5xl font-bold tracking-tight max-w-xl">From confused to confident in four steps.</h2>
            <div class="mt-14 grid md:grid-cols-4 gap-8">
                @foreach ($steps as $i => $s)
                    <div class="rv" style="transition-delay: {{ $i * .12 }}s">
                        <div class="flex items-center gap-3">
                            <span class="grid place-items-center w-10 h-10 rounded-full bg-brand-600 font-bold">{{ $i + 1 }}</span>
                            <span class="bar h-px flex-1 bg-white/20 hidden md:block"></span>
                        </div>
                        <h3 class="mt-5 text-lg font-semibold">{{ $s[0] }}</h3>
                        <p class="mt-2 text-slate-400 text-sm">{{ $s[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PRICING --}}
    <section id="pricing" class="py-24 px-5">
        <div class="mx-auto max-w-6xl">
           
            @include('components.pricing')
{{--             <div class="mt-12 grid md:grid-cols-3 gap-5 items-stretch">
                @foreach ($plans as $i => $p)
                    @php $hot = $p['hot'] ?? false; @endphp
                    <article class="rv relative rounded-3xl p-8 flex flex-col {{ $hot ? 'bg-[#0b1530] text-white shadow-2xl shadow-brand-600/20 ring-2 ring-brand-500 dark:bg-brand-600/20' : 'card' }}" style="transition-delay: {{ $i * .1 }}s">
                        @if ($hot)
                            <span class="absolute -top-3 left-8 text-[11px] font-bold px-3 py-1 rounded-full bg-brand-500 text-white">Most popular</span>
                        @endif
                        <h3 class="text-lg font-bold {{ $hot ? '' : 'text-[#0b1530] dark:text-white' }}">{{ $p['name'] }}</h3>
                        <p class="mt-3 text-5xl font-bold {{ $hot ? '' : 'text-[#0b1530] dark:text-white' }}">$<span data-m="{{ $p['m'] }}" data-y="{{ $p['y'] }}">{{ $p['m'] }}</span><span class="text-base font-medium opacity-60" data-per>/mo</span></p>
                        <ul class="mt-6 space-y-2.5 text-sm flex-1">
                            @foreach ($p['features'] as $f)
                                <li class="flex gap-2"><span class="text-brand-500">✓</span>{{ $f }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ url('/register?plan=' . strtolower($p['name'])) }}" class="mt-8 h-11 grid place-items-center rounded-full font-semibold transition {{ $hot ? 'bg-brand-500 hover:bg-brand-400 text-white' : 'border border-slate-300 dark:border-white/15 hover:bg-brand-600 hover:text-white hover:border-brand-600' }}">Choose {{ $p['name'] }}</a>
                    </article>
                @endforeach
            </div> --}}
        </div>
    </section>

    {{-- ABOUT --}}
    <section id="about" class="py-24 px-5">
        <div class="rv mx-auto max-w-5xl card rounded-[2rem] p-8 sm:p-14 grid md:grid-cols-[auto_1fr] gap-8 items-center">
            <svg viewBox="0 0 120 120" class="w-28 sm:w-36 mx-auto" fill="none">
                <circle cx="60" cy="60" r="56" class="fill-brand-50 dark:fill-brand-500/15"/>
                <path d="M60 22l30 12v22c0 20-13 34-30 42-17-8-30-22-30-42V34l30-12z" class="fill-brand-600"/>
                <path d="M45 60l11 11 20-22" stroke="#fff" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div>
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-[#0b1530] dark:text-white">Real credentials. Automated delivery.</h2>
                <p class="mt-4">Every guide and report is shaped by a licensed Enrolled Agent, the federal credential that allows representation before the IRS. We use AI to make that expertise affordable, not to replace it. We advise and educate; we don’t prepare or file your return.</p>
                <a href="{{ url('/about') }}" class="mt-6 inline-flex font-semibold text-brand-600 dark:text-brand-400 hover:underline">Read our story →</a>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="py-24 px-5 bg-white/60 dark:bg-white/[.02] border-y border-slate-200 dark:border-white/10">
        <div class="mx-auto max-w-2xl">
            <h2 class="rv text-3xl sm:text-4xl font-bold tracking-tight text-[#0b1530] dark:text-white">Questions, answered.</h2>
            <div class="mt-10 divide-y divide-slate-200 dark:divide-white/10 border-y border-slate-200 dark:border-white/10">
                @foreach ($faqs as $q)
                    <details class="rv py-5">
                        <summary class="flex justify-between gap-4 cursor-pointer font-semibold text-[#0b1530] dark:text-white">{{ $q[0] }}<span class="pl transition text-brand-600 text-xl leading-none">+</span></summary>
                        <p class="mt-3">{{ $q[1] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section id="cta" class="px-5 py-24">
        <div class="rv relative overflow-hidden mx-auto max-w-5xl rounded-[2rem] bg-gradient-to-br from-brand-600 to-brand-500 p-10 sm:p-16 text-center text-white">
            <div class="absolute -right-12 -top-12 w-60 h-60 rounded-full bg-white/10 blur-2xl animate-fl"></div>
            <h2 class="relative text-3xl sm:text-5xl font-bold tracking-tight">See what you could be missing.</h2>
            <p class="relative mt-4 text-blue-50 max-w-md mx-auto">Answer a few questions and get a personalized recommendation in minutes.</p>
            <a href="{{ url('/tax-screener') }}" class="relative mt-8 inline-flex h-12 px-8 items-center rounded-full bg-white text-brand-700 font-semibold hover:scale-105 transition">Get your free tax screener</a>
        </div>
    </section>
</div>


@push('scripts')
<script>
    const $$ = s => [...document.querySelectorAll(s)];

    // Monthly / yearly toggle
    $$('[data-bill]').forEach(b => b.onclick = () => {
        const yearly = b.dataset.bill === 'y';
        $$('[data-bill]').forEach(x => x.className = x === b
            ? 'px-5 py-2 rounded-full bg-brand-600 text-white transition'
            : 'px-5 py-2 rounded-full transition');
        $$('[data-m]').forEach(s => s.textContent = yearly ? s.dataset.y : s.dataset.m);
        $$('[data-per]').forEach(s => s.textContent = yearly ? '/yr' : '/mo');
    });

    // Counter
    const count = el => {
        const n = +el.dataset.count, start = performance.now();
        (function f(t) {
            const p = Math.min((t - start) / 1600, 1);
            el.textContent = Math.round(n * (1 - Math.pow(1 - p, 3))).toLocaleString('en-US');
            if (p < 1) requestAnimationFrame(f);
        })(start);
    };

    // Reveal on scroll
    const io = new IntersectionObserver(entries => entries.forEach(x => {
        if (x.isIntersecting) {
            x.target.classList.add('in');
            x.target.querySelectorAll('[data-count]').forEach(count);
            io.unobserve(x.target);
        }
    }), { threshold: .12 });
    $$('.rv').forEach(el => io.observe(el));
</script>
@endpush
</x-guest-layout>