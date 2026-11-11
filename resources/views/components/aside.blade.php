<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] flex-col border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 lg:w-64 -translate-x-full invisible transition-all duration-300 ease-[cubic-bezier(.32,.72,0,1)] lg:translate-x-0 lg:visible lg:transition-none pt-[env(safe-area-inset-top,0px)] pb-[env(safe-area-inset-bottom,0px)] focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2"
       aria-label="Main navigation">
  <div class="flex h-14 items-center justify-between px-4">
    <a href="/" class="flex items-center gap-2.5 rounded-lg focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2">
      <span class="grid  place-items-center p-2 rounded  bg-blue-900 dark:bg-blue-700 text-sm font-bold text-white">UNSTUCK </span>
      <span class="text-sm font-bold tracking-wide">MY TAXES</span>
    </a>
    <button id="closeNav" type="button" class="grid h-9 w-9 place-items-center rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-950 lg:hidden focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2" aria-label="Close menu">
      <svg class="h-5 w-5"><use href="#i-x"/></svg>
    </button>
  </div>

  <div class="border-t border-slate-200 dark:border-slate-800 px-3 py-3">
    <ul class="space-y-1">
       <li><a href="{{route("dashboard")}}" aria-current="page" class="flex items-center gap-3 rounded-xl bg-blue-900 dark:bg-blue-700 px-3 py-2.5 text-sm font-medium text-white focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2"><svg class="h-[18px] w-[18px]"><use href="#i-grid"/></svg>Dashboard</a></li>
   </ul>
 </div>

  <nav class="mt-2 flex-1 overflow-y-auto px-3" aria-label="Primary">
    <ul class="space-y-1">
      <li><a href="{{route('library.index')}}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors hover:bg-slate-50 dark:hover:bg-slate-950 hover:text-slate-800 dark:hover:text-slate-100 focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2"><svg class="h-[18px] w-[18px]"><use href="#i-library"/></svg>Library</a></li>
      <li><a href="{{route('documents')}}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors hover:bg-slate-50 dark:hover:bg-slate-950 hover:text-slate-800 dark:hover:text-slate-100 focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2"><svg class="h-[18px] w-[18px]"><use href="#i-library"/></svg>My documents</a></li>
      <li><a href="{{route('chat')}}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors hover:bg-slate-50 dark:hover:bg-slate-950 hover:text-slate-800 dark:hover:text-slate-100 focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2"><svg class="h-[18px] w-[18px]"><use href="#i-chat"/></svg>New chat</a></li>
       <li><a href="{{route('hystory')}}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors hover:bg-slate-50 dark:hover:bg-slate-950 hover:text-slate-800 dark:hover:text-slate-100 focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2"><svg class="h-[18px] w-[18px]"><use href="#i-library"/></svg>My hystory chats</a></li>
       <li><a href="{{route('reports')}}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors hover:bg-slate-50 dark:hover:bg-slate-950 hover:text-slate-800 dark:hover:text-slate-100 focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2"><svg class="h-[18px] w-[18px]"><use href="#i-library"/></svg>My generated reports</a></li>
              <li><a href="{{route('listprofiles')}}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors hover:bg-slate-50 dark:hover:bg-slate-950 hover:text-slate-800 dark:hover:text-slate-100 focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2"><svg class="h-[18px] w-[18px]"><use href="#i-library"/></svg>My tax profile</a></li>
      <li><a href="{{route("subscription.index")}}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors hover:bg-slate-50 dark:hover:bg-slate-950 hover:text-slate-800 dark:hover:text-slate-100 focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2"><svg class="h-[18px] w-[18px]"><use href="#i-users"/></svg>My subscription</a></li>
    </ul>
  </nav>

  <div class="border-t border-slate-200 dark:border-slate-800 px-3 py-3">
    <ul class="space-y-1">
      <li><a href="{{ route('subscription.upgrade') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors hover:bg-slate-50 dark:hover:bg-slate-950 hover:text-slate-800 dark:hover:text-slate-100 focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2"><svg class="h-[18px] w-[18px]"><use href="#i-crown"/></svg>Upgrade</a></li>
      <li>
          <button @click.prevent="$store.theme.toggle()"
             class="p-2 rounded-lg flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors hover:bg-slate-50 dark:hover:bg-slate-950 hover:text-slate-800 dark:hover:text-slate-100 focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2">
                    <span class="dark:hidden">                                            <svg class="dark:hidden" width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17.4547 11.97L18.1799 12.1611C18.265 11.8383 18.1265 11.4982 17.8401 11.3266C17.5538 11.1551 17.1885 11.1934 16.944 11.4207L17.4547 11.97ZM8.0306 2.5459L8.57989 3.05657C8.80718 2.81209 8.84554 2.44682 8.67398 2.16046C8.50243 1.8741 8.16227 1.73559 7.83948 1.82066L8.0306 2.5459ZM12.9154 13.0035C9.64678 13.0035 6.99707 10.3538 6.99707 7.08524H5.49707C5.49707 11.1823 8.81835 14.5035 12.9154 14.5035V13.0035ZM16.944 11.4207C15.8869 12.4035 14.4721 13.0035 12.9154 13.0035V14.5035C14.8657 14.5035 16.6418 13.7499 17.9654 12.5193L16.944 11.4207ZM16.7295 11.7789C15.9437 14.7607 13.2277 16.9586 10.0003 16.9586V18.4586C13.9257 18.4586 17.2249 15.7853 18.1799 12.1611L16.7295 11.7789ZM10.0003 16.9586C6.15734 16.9586 3.04199 13.8433 3.04199 10.0003H1.54199C1.54199 14.6717 5.32892 18.4586 10.0003 18.4586V16.9586ZM3.04199 10.0003C3.04199 6.77289 5.23988 4.05695 8.22173 3.27114L7.83948 1.82066C4.21532 2.77574 1.54199 6.07486 1.54199 10.0003H3.04199ZM6.99707 7.08524C6.99707 5.52854 7.5971 4.11366 8.57989 3.05657L7.48132 2.03522C6.25073 3.35885 5.49707 5.13487 5.49707 7.08524H6.99707Z"
                            fill="currentColor" />
                    </svg></span>
                    <span class="hidden dark:inline">
                                                                    <svg class="hidden dark:block" width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M9.99998 1.5415C10.4142 1.5415 10.75 1.87729 10.75 2.2915V3.5415C10.75 3.95572 10.4142 4.2915 9.99998 4.2915C9.58577 4.2915 9.24998 3.95572 9.24998 3.5415V2.2915C9.24998 1.87729 9.58577 1.5415 9.99998 1.5415ZM10.0009 6.79327C8.22978 6.79327 6.79402 8.22904 6.79402 10.0001C6.79402 11.7712 8.22978 13.207 10.0009 13.207C11.772 13.207 13.2078 11.7712 13.2078 10.0001C13.2078 8.22904 11.772 6.79327 10.0009 6.79327ZM5.29402 10.0001C5.29402 7.40061 7.40135 5.29327 10.0009 5.29327C12.6004 5.29327 14.7078 7.40061 14.7078 10.0001C14.7078 12.5997 12.6004 14.707 10.0009 14.707C7.40135 14.707 5.29402 12.5997 5.29402 10.0001ZM15.9813 5.08035C16.2742 4.78746 16.2742 4.31258 15.9813 4.01969C15.6884 3.7268 15.2135 3.7268 14.9207 4.01969L14.0368 4.90357C13.7439 5.19647 13.7439 5.67134 14.0368 5.96423C14.3297 6.25713 14.8045 6.25713 15.0974 5.96423L15.9813 5.08035ZM18.4577 10.0001C18.4577 10.4143 18.1219 10.7501 17.7077 10.7501H16.4577C16.0435 10.7501 15.7077 10.4143 15.7077 10.0001C15.7077 9.58592 16.0435 9.25013 16.4577 9.25013H17.7077C18.1219 9.25013 18.4577 9.58592 18.4577 10.0001ZM14.9207 15.9806C15.2135 16.2735 15.6884 16.2735 15.9813 15.9806C16.2742 15.6877 16.2742 15.2128 15.9813 14.9199L15.0974 14.036C14.8045 13.7431 14.3297 13.7431 14.0368 14.036C13.7439 14.3289 13.7439 14.8038 14.0368 15.0967L14.9207 15.9806ZM9.99998 15.7088C10.4142 15.7088 10.75 16.0445 10.75 16.4588V17.7088C10.75 18.123 10.4142 18.4588 9.99998 18.4588C9.58577 18.4588 9.24998 18.123 9.24998 17.7088V16.4588C9.24998 16.0445 9.58577 15.7088 9.99998 15.7088ZM5.96356 15.0972C6.25646 14.8043 6.25646 14.3295 5.96356 14.0366C5.67067 13.7437 5.1958 13.7437 4.9029 14.0366L4.01902 14.9204C3.72613 15.2133 3.72613 15.6882 4.01902 15.9811C4.31191 16.274 4.78679 16.274 5.07968 15.9811L5.96356 15.0972ZM4.29224 10.0001C4.29224 10.4143 3.95645 10.7501 3.54224 10.7501H2.29224C1.87802 10.7501 1.54224 10.4143 1.54224 10.0001C1.54224 9.58592 1.87802 9.25013 2.29224 9.25013H3.54224C3.95645 9.25013 4.29224 9.58592 4.29224 10.0001ZM4.9029 5.9637C5.1958 6.25659 5.67067 6.25659 5.96356 5.9637C6.25646 5.6708 6.25646 5.19593 5.96356 4.90303L5.07968 4.01915C4.78679 3.72626 4.31191 3.72626 4.01902 4.01915C3.72613 4.31204 3.72613 4.78692 4.01902 5.07981L4.9029 5.9637Z"
                            fill="currentColor" />
                    </svg>
                    </span> Theme
                </button>
              </li>


      <li><a href="/profile" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors hover:bg-slate-50 dark:hover:bg-slate-950 hover:text-slate-800 dark:hover:text-slate-100 focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2"><svg class="h-[18px] w-[18px]"><use href="#i-settings"/></svg>Settings</a></li>
    </ul>
    <p class="mt-2 truncate px-3 text-xs text-slate-500 dark:text-slate-400" title="you@example.com">{{auth()->user()->email}}</p>
     <form method="POST" action="{{route("logout")}}">
      @csrf
    <button type="wire:submit="save"" class="mt-1 flex bg-red-500 text-white w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2">
      <svg class="h-[18px] w-[18px]"><use href="#i-logout"/></svg>Log out
    </button>
  </form>
  </div>
</aside>