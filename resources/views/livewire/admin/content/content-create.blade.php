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
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Fill all the informations below to publish the content</p>
        </div>

        <div class="space-y-6">

            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Title
                </label>
                <input
                    type="text"
                    wire:model="title"
                    placeholder="Ex: How to ..."
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors placeholder-gray-400 dark:placeholder-gray-500 px-5 py-3"
                >
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Type
                    </label>
                    <select
                        wire:model.live="type"
                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors px-5 py-3"
                    >
                        <option value="blog">Blog</option>
                        <option value="video">Video</option>
                        <option value="document">Document</option>
                        <option value="pack">Pack</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Access Level
                    </label>
                    <select
                        wire:model="access_level"
                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors px-5 py-3"
                    >
                        <option value="free">Free</option>
                        <option value="pro">Pro</option>
                        <option value="premium">Premium</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Excerpt
                </label>
                <textarea
                    wire:model="excerpt"
                    rows="3"
                    placeholder="Un bref résumé du contenu..."
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors placeholder-gray-400 dark:placeholder-gray-500 px-5 py-3 resize-none"
                ></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-gray-800/40 p-5 rounded-xl border border-gray-100 dark:border-gray-800 transition-colors">
                
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Thumbnail (Image)
                    </label>
                    <input
                        type="file"
                        wire:model="thumbnail"
                        accept="image/*"
                        class="block w-full text-sm text-gray-500 dark:text-gray-400
                            file:mr-4 file:py-2.5 file:px-4
                            file:rounded-xl file:border-0
                            file:text-sm file:font-bold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100
                            dark:file:bg-blue-500/10 dark:file:text-blue-400 dark:hover:file:bg-blue-500/20
                            transition cursor-pointer"
                    >
                    @if ($thumbnail)
                        <div class="mt-4 relative rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm inline-block">
                            <img src="{{ $thumbnail->temporaryUrl() }}" class="h-32 w-auto object-cover">
                        </div>
                    @endif
                    @error('thumbnail') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    @if($type === 'document')
                        <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            PDF Document
                        </label>
                        <input
                            type="file"
                            wire:model="document"
                            accept=".pdf"
                            class="block w-full text-sm text-gray-500 dark:text-gray-400 
                                file:mr-4 file:py-2.5 file:px-4 
                                file:rounded-xl file:border-0 
                                file:text-sm file:font-bold 
                                file:bg-blue-50 file:text-blue-700 
                                hover:file:bg-blue-100 
                                dark:file:bg-blue-500/10 dark:file:text-blue-400 dark:hover:file:bg-blue-500/20
                                transition cursor-pointer"
                        >
                        @error('document') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

                    @elseif($type === 'video')
                        <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Video File (MP4, AVI)
                        </label>
                        <input
                            type="file"
                            wire:model="video"
                            accept="video/*"
                            class="block w-full text-sm text-gray-500 dark:text-gray-400 
                                file:mr-4 file:py-2.5 file:px-4 
                                file:rounded-xl file:border-0 
                                file:text-sm file:font-bold 
                                file:bg-blue-50 file:text-blue-700 
                                hover:file:bg-blue-100 
                                dark:file:bg-blue-500/10 dark:file:text-blue-400 dark:hover:file:bg-blue-500/20
                                transition cursor-pointer"
                        >
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Taille maximale : 50 Mo.</p>
                        @error('video') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        
                        <div wire:loading wire:target="video" class="text-sm text-blue-500 mt-2 font-medium">
                            Upload en cours, veuillez patienter...
                        </div>
                    @else
                        <div class="text-sm text-gray-400 dark:text-gray-500 italic h-full flex items-center pt-6 md:pt-8">
                            Aucun fichier additionnel requis pour ce type de contenu.
                        </div>
                    @endif
                </div>
            </div>

            <div wire:ignore class="w-full">
                <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Content
                </label>
                <textarea
                    id="edit"
                    placeholder="Rédigez votre contenu détaillé ici..."
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm"
                >{{ $content }}</textarea>
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
                        @this.set('content', editor.getData());
                    });
                })
                .catch(error => {
                    console.error('Erreur CKEditor:', error);
                });
        });
    </script>
</div>