<div class="w-full max-w-4xl mx-auto px-4 sm:px-6 py-8">
    <style>
        .dark .ck-editor__creator_inline,
        .dark .ck-content,
        .dark .ck-toolbar {
            background-color: #1f2937 !important; /* bg-gray-800 */
            color: #f3f4f6 !important; /* text-gray-100 */
            border-color: #374151 !important; /* border-gray-700 */
        }
        .dark .ck-toolbar button:hover,
        .dark .ck-dropdown__panel {
            background-color: #374151 !important;
        }
        .dark .ck-toolbar button {
            color: #f3f4f6 !important;
        }
        .dark .ck-list {
            background-color: #1f2937 !important;
        }
        .dark .ck-list__item:hover {
            background-color: #374151 !important;
        }
        .ck-editor__main {
            min-height: 250px;
        }
    </style>

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 sm:p-8 transition-colors duration-200">

        <div class="mb-8 border-b border-gray-100 dark:border-gray-800 pb-5">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Create Content
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Fill all the all the informations.</p>
        </div>

        <div class="space-y-6">

            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Name
                </label>
                <input
                    type="text"
                    wire:model="name"
                    placeholder="Ex: How to ..."
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors placeholder-gray-400 dark:placeholder-gray-500 px-5 py-3"
                >
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        price
                    </label>
                                  <input
                    type="text"
                    wire:model="price"
                    placeholder="Ex: $20"
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors placeholder-gray-400 dark:placeholder-gray-500 px-5 py-3"
                >
                </div>
                            <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Tokens
                    </label>
                                  <input
                    type="text"
                    wire:model="tokens"
                    placeholder="ex: 2000"
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors placeholder-gray-400 dark:placeholder-gray-500 px-5 py-3"
                >
                </div>


            </div>


            <div wire:ignore class="w-full">
                <label class="block mb-2 text-sm font-semibold text-black dark:text-gray-300">
                    description
                </label>
                <textarea
                    wire:model="description"
                    id="edit"
                    placeholder="Rédigez votre contenu détaillé ici..."
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm"
                >{{$description}}</textarea>
            </div>

            <div class="pt-6 mt-6 border-t border-gray-100 dark:border-gray-800 flex justify-end">
                <button
                    wire:click="save"
                    class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-500 dark:bg-blue-500 dark:hover:bg-blue-400 text-white dark:text-gray-950 font-bold tracking-wide shadow-sm transition transform active:scale-95"
                >
                    Publish Content
                </button>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            ClassicEditor
                .create(document.querySelector('#edit'), {
                    ckfinder: {
                        uploadUrl: "{{ route('ckeditor.upload') }}?_token={{ csrf_token() }}"
                    },
                    toolbar: [ 
                        'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 
                        '|', 'uploadImage', 'insertTable', 'blockQuote', 'undo', 'redo' 
                    ]
                })
                .then(editor => {
                    // Synchronisation des données vers Livewire à chaque modification
                    editor.model.document.on('change:data', () => {
                        @this.set('description', editor.getData());
                    });
                })
                .catch(error => {
                    console.error('Erreur CKEditor:', error);
                });
        });
    </script>
</div>