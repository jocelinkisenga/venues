<div class="w-full min-h-screen bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">

 {{-- HEADER --}}
 <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

  <div>
   <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
    Generated Ai reports
   </h1>
  </div>

  <div class="flex gap-3">

 {{--   <input
   wire:model.live="search"
   type="text"
   placeholder="Search reports..."
   class="rounded-xl border px-4 py-2 bg-white dark:text-white border-gray-300 dark:border-gray-800 bg-white dark:bg-gray-900"
   > --}}

   <select
    wire:model.live="type"
    class="rounded-xl border border-gray-300 dark:border-gray-800 bg-white dark:bg-gray-900 px-4 py-2 bg-white text-gray-900 dark:text-white "
    >
    <option value="">All Types</option>
    <option value="tax">Tax</option>
    <option value="business">Business</option>
    <option value="finance">Finance</option>
    <option value="general">General</option>
   </select>

  </div>

 </div>

 {{-- MOBILE --}}
 <div class="grid gap-4 md:hidden">

  @forelse($reports as $report)

  <div
   class="bg-white dark:bg-gray-900 dark:text-white
   border border-gray-200 dark:border-gray-800
   rounded-3xl p-5 shadow-sm"
   >

   <div class="flex justify-between items-start">

    <h3 class="font-semibold text-gray-900 dark:text-white">
     {{ $report->title }}
    </h3>

    <span
     class="text-xs px-2 py-1 rounded-full
     {{ $report->isCompleted()
     ? 'bg-green-100 text-green-700'
     : 'bg-yellow-100 text-yellow-700' }}"
     >
     {{ ucfirst($report->status) }}
    </span>

   </div>

   <p class="mt-3 text-sm text-gray-500 line-clamp-3">
    {{ $report->summary }}
   </p>

   <div class="mt-4 flex flex-wrap gap-2">

    <span class="text-xs px-2 py-1 rounded-xl bg-gray-100 dark:bg-gray-800">
     {{ ucfirst($report->type) }}
    </span>

    @if($report->confidence_score)

    <span class="text-xs px-2 py-1 rounded-xl bg-blue-100 text-blue-700">
     {{ $report->confidence_score }}%
    </span>

    @endif

    @if($report->hasPdf())

    <span class="text-xs px-2 py-1 rounded-xl bg-purple-100 text-purple-700">
     PDF
    </span>

    @endif

   </div>

   <div class="mt-4 flex justify-between items-center">

    <span class="text-xs text-gray-400">
     {{ $report->created_at->diffForHumans() }}
    </span>

    <a href="{{route("report.show", ["reportId" => $report->id])}}"
     class="px-4 py-2 rounded-xl bg-black text-white dark:bg-white dark:text-black text-sm"
     >
     View
    </a>

   </div>

  </div>

  @empty

  <div class="text-center py-10 text-gray-500">
   No reports found.
  </div>

  @endforelse

 </div>

 {{-- DESKTOP TABLE --}}
 <div class="hidden md:block">

  <div class="overflow-hidden rounded-3xl border border-gray-200 dark:border-gray-800">

   <table class="w-full">

    <thead class="bg-gray-50 dark:bg-gray-900">

     <tr>

      <th class="text-left p-4">
       Title
      </th>

      <th class="text-left p-4">
       Type
      </th>

      <th class="text-left p-4">
       Status
      </th>

      <th class="text-left p-4">
       Confidence
      </th>

      <th class="text-left p-4">
       PDF
      </th>

      <th class="text-left p-4">
       Date
      </th>

      <th class="text-right p-4">
       Action
      </th>

     </tr>

    </thead>

    <tbody>

     @foreach($reports as $report)

     <tr class="border-t border-gray-200 dark:border-gray-800">

      <td class="p-4">

       <div>

        <div class="font-medium">
         {!! $report->title !!}
        </div>

{{--         <div class="text-sm text-gray-500">
         {!! Str::limit($report->summary,80) !!}
        </div> --}}

       </div>

      </td>

      <td class="p-4">
       {{ ucfirst($report->type) }}
      </td>

      <td class="p-4">

       <span
        class="px-2 py-1 rounded-full text-xs
        {{ $report->isCompleted()
        ? 'bg-green-100 text-green-700'
        : 'bg-yellow-100 text-yellow-700' }}"
        >
        {{ ucfirst($report->status) }}
       </span>

      </td>

      <td class="p-4">
       {{ $report->confidence_score ?? '--' }}
      </td>

      <td class="p-4">

       @if($report->hasPdf())
       ✅
       @else
       —
       @endif

      </td>

      <td class="p-4">
       {{ $report->created_at->format('M d, Y') }}
      </td>

      <td class="p-4 text-right">

       <a href="{{route("report.show", ["reportId" => $report->id])}}"
        class="px-4 py-2 rounded-xl bg-black text-white dark:bg-white dark:text-black"
        >
        View
       </a>

      </td>

     </tr>

     @endforeach

    </tbody>

   </table>

  </div>

 </div>

 <div class="mt-6">
  {{ $reports->links() }}
 </div>

</div>