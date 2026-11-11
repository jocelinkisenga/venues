{{-- Documents Center --}}
<section class="mt-10">
 @php
    // Définition d'un utilitaire local pour éviter l'erreur de contexte $this
    $formatFileSize = function ($path) {
        if (! $path || ! \Illuminate\Support\Facades\Storage::exists($path)) {
            return '0';
        }
        $bytes = \Illuminate\Support\Facades\Storage::size($path);
        // Conversion directe en MegaOctets (MB) pour correspondre à ton affichage
        $megabytes = $bytes / (1024 * 1024);
        return number_format($megabytes, 2);
    };

    $documents = auth()->user()->chatMessages()
        ->whereNotNull('file_path') // Uniquement les messages avec un fichier
        ->where('role', 'user')      // Uniquement ceux envoyés par l'utilisateur
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($message) use ($formatFileSize) {
            return [
                'id'             => $message->id,
                'name'           => $message->file_name,
                'type'           => $message->file_type ?? 'PDF',
                'size'           => $formatFileSize($message->file_path),
                'date'           => $message->created_at->format('d/m/Y H:i'),
                'context_prompt' => $message->message,
            ];
        });
 @endphp

 {{-- Header --}}
 <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between mb-6">
  <div>
   <h3 class="text-xl font-bold text-slate-900 dark:text-white">
    Documents Center
   </h3>
   <p class="text-sm text-slate-500 dark:text-slate-400">
    Manage, analyze and organize your financial documents.
   </p>
  </div>

  {{-- Filters (UI only) --}}
  <div class="flex gap-2 overflow-x-auto md:overflow-visible">
   <button class="px-3 py-1.5 text-xs font-semibold rounded-full bg-slate-900 text-white dark:bg-white dark:text-slate-900">
    All
   </button>
   <button class="px-3 py-1.5 text-xs font-semibold rounded-full border border-slate-200 text-slate-600 dark:border-slate-800 dark:text-slate-300">
    Invoices
   </button>
   <button class="px-3 py-1.5 text-xs font-semibold rounded-full border border-slate-200 text-slate-600 dark:border-slate-800 dark:text-slate-300">
    Receipts
   </button>
   <button class="px-3 py-1.5 text-xs font-semibold rounded-full border border-slate-200 text-slate-600 dark:border-slate-800 dark:text-slate-300">
    Tax Forms
   </button>
  </div>
 </div>

 {{-- Grid --}}
 <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
  @foreach($documents as $document)
  {{-- Document Card --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900 hover:shadow-md transition">
   <div class="flex items-start justify-between">
    <div class="flex items-center gap-3">
     <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center dark:bg-red-500/10">
      📄
     </div>
     <div>
      <h4 class="text-sm font-semibold text-slate-900 dark:text-white truncate max-w-[150px]" title="{{ $document['name'] }}">
       {{ $document['name'] }}
      </h4>
      <p class="text-xs text-slate-500 uppercase">
       {{ $document['type'] }} • {{ $document['size'] }} MB
      </p>
     </div>
    </div>
    <span class="text-xs px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
     Processed
    </span>
   </div>

   <div class="mt-4">
    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 italic" title="{{ $document['context_prompt'] }}">
     "{{ Str::limit($document['context_prompt'] ?? 'AI extracted income data successfully.', 60) }}"
    </p>
    <div class="mt-3 flex items-center justify-between text-xs text-slate-400">
     <span>{{ $document['date'] }}</span>
     <button class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">
      View
     </button>
    </div>
   </div>
  </div>
  @endforeach
 </div>

 {{-- Upload Zone --}}
 <div class="mt-6 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 p-8 text-center">
  <div class="text-3xl mb-2">⬆️</div>
  <h4 class="text-sm font-semibold text-slate-900 dark:text-white">
   Upload new document
  </h4>
  <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
   Drag & drop or click to upload W-2, 1099, receipts, or invoices
  </p>
  <button class="mt-4 px-4 py-2 rounded-xl bg-slate-900 text-white dark:bg-white dark:text-slate-900 text-sm font-semibold hover:opacity-90 transition">
   Upload File
  </button>
 </div>
</section>
