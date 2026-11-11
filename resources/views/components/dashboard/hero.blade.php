<div
 class="relative overflow-hidden bg-white text-gray-900 dark:bg-black dark:text-white rounded-3xl border border-slate-200 p-6 shadow-sm dark:border-slate-800 md:p-8">
 @php
 $documentsCount = auth()->user()->chatMessages()->whereNotNull('file_path')->where('role', 'user')->count();
 @endphp

 {{-- Background --}}
 <div
  class="absolute inset-0 bg-white text-gray-900 dark:bg-black dark:text-white">
  
 </div>

 <div class="relative z-10">

  <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

   {{-- Left --}}
   <div>

 
    <h1
     class="max-w-3xl text-3xl font-black tracking-tight text-slate-900 dark:text-white md:text-5xl">

     Welcome back,
     {{ auth()->user()->name ?? 'User' }}

    </h1>

    <p
     class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600 dark:text-slate-900 md:text-lg">

     Manage your tax intelligence, financial reports,
     document analysis and AI conversations from a single dashboard.

    </p>

   </div>

   {{-- Right --}}
   <div
    class="grid grid-cols-2 gap-3 lg:w-[340px]">

    <div
     class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">

     <p
      class="text-xs uppercase tracking-wider text-slate-500">

      Reports

     </p>

     <h3
      class="mt-2 text-2xl font-black text-black dark:text-white">

      {{auth()->user()->reports()->count()}}

     </h3>

    </div>

    <div
     class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">

     <p
      class="text-xs uppercase tracking-wider text-slate-500">

      Documents

     </p>

     <h3
      class="mt-2 text-2xl font-black text-black dark:text-white">

      {{$documentsCount}}

     </h3>

    </div>

    <div
     class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">

     <p
      class="text-xs uppercase tracking-wider text-slate-500">

      AI Chats

     </p>

     <h3
      class="mt-2 text-2xl font-black text-black dark:text-white">

                              {{auth()->user()->chats()->count()}}

     </h3>

    </div>

{{--     <div
     class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">

     <p
      class="text-xs uppercase tracking-wider text-slate-500">

      Profile

     </p>

     <h3
      class="mt-2 text-2xl font-black text-emerald-600">

      80%

     </h3>

    </div> --}}

   </div>

  </div>

 </div>

</div>