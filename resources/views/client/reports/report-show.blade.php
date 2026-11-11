<x-client-layout>
     @php 
     $settings = \App\Models\AiSetting::first();
     $defaultModel = \App\Models\AiModel::whereId($settings->primary_model_id)->first();
     
     @endphp
 <div class="min-h-screen bg-gray-50 dark:bg-gray-950">

  {{-- HEADER --}}
  <div class="border-b bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800">

   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">

     <div>

      <div class="flex items-center gap-3">

       <span
        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
        bg-gray-100 dark:bg-gray-800
        text-gray-700 dark:text-gray-300"
        >
        AI REPORT
       </span>

       <span
        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
        {{ $report->status === 'completed'
        ? 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400'
        : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400'
        }}"
        >
        {{ ucfirst($report->status) }}
       </span>

      </div>

      <h1 class="mt-4 text-3xl font-bold text-gray-900 dark:text-white">
       {{ $report->title }}
      </h1>

      {{-- @if($report->summary) --}}
{{--       <p class="mt-4 text-gray-600 dark:text-gray-400 max-w-4xl">
       {!! $report->summary !!}
      </p>
      @endif --}}

     </div>

     {{-- ACTIONS --}}
     <div class="flex flex-wrap gap-3">

      @if($report->pdf_path)

      <a
       href="{{ Storage::url($report->pdf_path) }}"
       target="_blank"
       class="px-5 py-3 rounded-2xl bg-black text-white
       dark:bg-white dark:text-black
       font-medium"
       >
       Download PDF
      </a>

      @endif

      <button
       onclick="window.print()"
       class="px-5 py-3 rounded-2xl border
       border-gray-300 dark:border-gray-700
       text-gray-700 dark:text-gray-300"
       >
       Print
      </button>

     </div>

    </div>

   </div>

  </div>

  {{-- CONTENT --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

   {{-- REPORT STATS --}}
   <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl p-5">
     <p class="text-xs uppercase tracking-wider text-gray-500">
      Report Type
     </p>

     <p class="mt-2 font-semibold text-gray-900 dark:text-white">
      {{ strtoupper($report->type) }}
     </p>
    </div>

{{--     <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl p-5">
     <p class="text-xs uppercase tracking-wider text-gray-500">
      Confidence
     </p>

     <p class="mt-2 font-semibold text-gray-900 dark:text-white">
      {{ $report->confidence_score ?? '--' }}%
     </p>
    </div> --}}

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl p-5">
     <p class="text-xs uppercase tracking-wider text-gray-500">
      Model
     </p>

     <p class="mt-2 font-semibold text-gray-900 dark:text-white">
      {{ $defaultModel->name ?? 'Gemini' }}
     </p>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl p-5">
     <p class="text-xs uppercase tracking-wider text-gray-500">
      Generated
     </p>

     <p class="mt-2 font-semibold text-gray-900 dark:text-white">
{{ optional($report->created_at)->format('M d, Y') ?? 'N/A' }}
     </p>
    </div>

   </div>

   {{-- SOURCE FILE --}}
   @if($report->source_file)

   <div class="mt-6 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl p-5">

    <div class="flex items-center gap-4">

     <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
      📄
     </div>

     <div>

      <p class="text-sm text-gray-500">
       Source Document
      </p>

      <p class="font-medium text-gray-900 dark:text-white">
       {{ basename($report->source_file) }}
      </p>

     </div>

    </div>

   </div>

   @endif

   {{-- META DATA --}}
   @if(!empty($report->meta))

   <div class="mt-6 grid md:grid-cols-2 lg:grid-cols-4 gap-4">

    @foreach($report->meta as $key => $value)

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl p-5">

     <p class="text-xs uppercase tracking-wider text-gray-500">
      {{ \Illuminate\Support\Str::headline($key) }}
     </p>

     <p class="mt-2 font-semibold text-gray-900 dark:text-white">
      {{ is_array($value) ? json_encode($value) : $value }}
     </p>

    </div>

    @endforeach

   </div>

   @endif

   {{-- REPORT BODY --}}
   <div class="mt-8">

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl overflow-hidden">

     {{-- DOCUMENT HEADER --}}
     <div class="border-b border-gray-200 dark:border-gray-800 p-6">

      <h2 class="text-xl font-bold text-gray-900 dark:text-white">
       Report Content
      </h2>

      <p class="text-sm text-gray-500 mt-1">
       Generated by {{ $defaultModel->name  ?? 'AI Engine' }}
      </p>

     </div>

     {{-- DOCUMENT BODY --}}
     <div class="p-6 lg:p-10">

      <article
       class="
       text-black dark:text-white
       prose
       prose-gray
       dark:prose-invert
       prose-lg
       max-w-none

       prose-headings:font-bold
       prose-headings:text-gray-900
       dark:prose-headings:text-white

       prose-p:text-gray-700
       dark:prose-p:text-gray-300

       prose-strong:text-gray-900
       dark:prose-strong:text-white

       prose-table:w-full
       prose-table:border-collapse

       prose-th:border
       prose-th:p-3

       prose-td:border
       prose-td:p-3
       "
       >

       {!! Str::markdown($report->content ?? '') !!}

      </article>

     </div>

    </div>

   </div>

  </div>

 </div>
</x-client-layout>