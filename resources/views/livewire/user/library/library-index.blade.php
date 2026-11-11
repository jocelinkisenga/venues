@php
use App\Enums\SubscriptionPlan;
@endphp
<div class="min-h-screen bg-gray-50 dark:bg-black">

 <div class="max-w-7xl mx-auto px-6 py-10">

  <!-- HEADER -->

  <div class="mb-10">

   <h1 class="text-4xl font-bold text-gray-900 dark:text-white">
    Content Library
   </h1>

   <p class="mt-3 text-gray-500 dark:text-gray-400">
    Explore premium tax education resources.
   </p>

  </div>

  <!-- FILTERS -->

  <div class="flex flex-col md:flex-row gap-4 mb-10">

   <!-- SEARCH -->

   <input
   type="text"
   wire:model.live.debounce.300ms="search"
   placeholder="Search content..."
   class="w-full md:w-96 rounded-2xl border border-gray-300 dark:border-gray-800 bg-white dark:bg-gray-900 px-5 py-3 text-gray-900 dark:text-white"
   >

   <!-- TYPE FILTER -->

   <select
    wire:model.live="type"
    class="rounded-2xl border border-gray-300 dark:border-gray-800 bg-white dark:bg-gray-900 px-5 py-3 text-gray-900 dark:text-white"
    >

    <option value="">All Types</option>

    <option value="video">Videos</option>

    <option value="blog">Blogs</option>

    <option value="document">Documents</option>

    <option value="pack">Packs</option>

   </select>

  </div>

  <!-- CONTENT GRID -->

  <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">

   @forelse($contents as $content)

   <div class="group rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm hover:shadow-xl transition duration-300">

    <!-- THUMBNAIL -->

    <div class="relative h-52 overflow-hidden">

     @if($content->thumbnail)

     <img
     src="{{ asset('storage/' . $content->thumbnail) }}"
     class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
     >

     @else

     <div class="w-full h-full bg-gray-200 dark:bg-gray-800"></div>

     @endif

     <!-- TYPE BADGE -->

     <div class="absolute top-4 left-4">

      <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-500/90 text-white backdrop-blur">

       {{ strtoupper($content->type) }}

      </span>

     </div>

     <!-- ACCESS BADGE -->

     <div class="absolute top-4 right-4">

      @if($content->access_level === SubscriptionPlan::FREE->value)

      <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-500 text-white">
       FREE
      </span>

      @elseif( $content->access_level === SubscriptionPlan::PRO->value)

      <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-500 text-black">
       PRO
      </span>

      @else

      <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-500 text-white">
       PREMIUM
      </span>

      @endif

     </div>

    </div>

    <!-- BODY -->

    <div class="p-6">

     <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">

      {{ $content->title }}

     </h2>

     <p class="text-gray-500 dark:text-gray-400 text-sm line-clamp-3">

      {{ $content->excerpt }}

     </p>

     <!-- CTA -->

     <div class="mt-6">

      @auth

      @if(auth()->user()->hasAccessTo($content))

      <a
       href="{{ route('library.show', $content->slug) }}"
       class="inline-flex items-center px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-medium transition"
       >
       View Content
      </a>

      @else

      <button
       class="inline-flex items-center px-5 py-3 rounded-xl bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300"
       >
       Upgrade Required
      </button>

      @endif

      @endauth

     </div>

    </div>

   </div>

   @empty

   <div class="col-span-full">

    <div class="rounded-3xl border border-dashed border-gray-300 dark:border-gray-700 p-16 text-center">

     <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
      No content found
     </h3>

     <p class="mt-3 text-gray-500 dark:text-gray-400">
      Try another keyword or filter.
     </p>

    </div>

   </div>

   @endforelse

  </div>

 </div>

</div>