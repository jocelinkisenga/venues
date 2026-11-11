<x-app-client>
 <div class="w-full min-h-screen bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">

  <div class="max-w-6xl mx-auto bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

   {{-- HEADER --}}
   <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
     Conversation history
    </h2>
    <p class="text-sm text-gray-500 dark:text-gray-400">
     find all your conversations here
    </p>
   </div>

   {{-- EMPTY STATE --}}
   @if(count($chats) === 0)
   <div class="p-8 text-center">
    <div class="text-sm text-gray-500 dark:text-gray-400">
     no conversation find .
    </div>
   </div>
   @else

   {{-- MOBILE VIEW (CARDS) --}}
   <div class="sm:hidden space-y-3 p-4">
    @foreach($chats as $chat)
    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition">

     <div class="flex justify-between items-start">
      <div>
       <div class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-[220px]">
        {{ $chat->title }}
       </div>

       <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
        #{{ $chat->id }} • {{ $chat->updated_at->diffForHumans() }}
       </div>
      </div>
     </div>

     <div class="mt-3">
      <a href="{{ route('chat', ['chatId' => $chat->id]) }}"
       class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
       open →
      </a>
     </div>

    </div>
    @endforeach
   </div>

   {{-- DESKTOP VIEW (TABLE) --}}
   <div class=" hidden md:block overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

     <thead class="bg-gray-50 dark:bg-gray-700">
      <tr>
       <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">
        ID
       </th>
       <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">
        Title
       </th>
       <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">
        Last update
       </th>
       <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">
        Action
       </th>
      </tr>
     </thead>

     <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

      @foreach($chats as $chat)
      <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">

       <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
        #{{ $chat->id }}
       </td>

       <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">
        {{ $chat->title }}
       </td>

       <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
        {{ $chat->updated_at->diffForHumans() }}
       </td>

       <td class="px-6 py-4 text-sm">
        <a href="{{ route('chat', ['chatId' => $chat->id]) }}"
         class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
         open
        </a>
       </td>

      </tr>
      @endforeach

     </tbody>
    </table>
   </div>

   @endif

  </div>
 </div>
</x-app-client>