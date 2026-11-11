<div>
<div class="bg-white text-gray-900 dark:bg-black dark:text-white transition-colors min-h-screen">
    <section class="relative py-20 overflow-hidden border-b border-blue-500/10">
        {{-- <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 via-black to-black"></div> --}}
        
        <div class="relative max-w-5xl mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold">Get in <span class="text-blue-500">Touch</span></h1>
            <p class="mt-6 text-gray-400 text-lg max-w-2xl mx-auto italic">
                Secure, confidential, and expert-led tax advisory starts here.
            </p>
        </div>
    </section>

    <section class="py-8">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                
                <div class="space-y-6">
                    <h2 class="text-2xl font-bold mb-8">Professional Channels</h2>

                    <div class="group bg-white/5 backdrop-blur-xl border border-blue-500 dark:border-blue-500/10 rounded-2xl p-6 hover:border-blue-500/40 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-blue-500/10 rounded-xl text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 uppercase tracking-widest font-semibold">Email</p>
                                <a href="mailto:contact@unstackmytaxes.com" class="text-xl font-bold hover:text-blue-400 transition-colors">contact@unstackmytaxes.com</a>
                            </div>
                        </div>
                    </div>

                    <div class="group bg-white/5 backdrop-blur-xl border border-blue-500 dark:border-blue-500/10 rounded-2xl p-6 hover:border-blue-500/40 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-blue-500/10 rounded-xl text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 uppercase tracking-widest font-semibold">Phone (U.S.)</p>
                                <p class="text-xl font-bold">+1 (555) 000-unstackmytaxes</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/5 backdrop-blur-xl border border-blue-500 dark:border-blue-500/10 rounded-2xl p-6">
                        <h3 class="text-sm text-gray-500 uppercase tracking-widest font-semibold mb-4">Advisory Hours</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between border-b border-white/5 pb-2">
                                <span class="text-gray-300">Monday - Friday</span>
                                <span class="text-blue-500 font-bold">9:00 AM – 6:00 PM EST</span>
                            </div>
                            <div class="flex justify-between pt-2">
                                <span class="text-gray-300">Saturday - Sunday</span>
                                <span class="text-gray-500 italic">By Appointment Only</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/5 backdrop-blur-xl border border-blue-500 dark:border-blue-500/20 rounded-3xl p-8 lg:p-12 shadow-[0_0_50px_rgba(34,197,94,0.05)]">
                    <form wire:submit.prevent="sendMessage" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-control">
                                <label class="label"><span class="label-text text-gray-400">Full Name</span></label>
                                <input type="text" wire:model="name" class="input bg-slate-50 dark:bg-white/5 border border-slate-300 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-500 focus:outline-none focus:border-blue-600 dark:focus:border-blue-500rounded-xl outline-none" placeholder="Jack Ricks">
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text text-gray-400">Email Address</span></label>
                                <input type="email" wire:model="email" class="input bg-slate-50 dark:bg-white/5 border border-slate-300 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-500 focus:outline-none focus:border-blue-600 dark:focus:border-blue-500 rounded-xl outline-none" placeholder="jackes@example.com">
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text text-gray-400">Subject</span></label>
                            <input wire:model="subject" class=" bg-slate-50 dark:bg-white/5 border border-slate-300 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-500 focus:outline-none focus:border-blue-600 dark:focus:border-blue-500 rounded-xl outline-none" placeholder="your subject" />
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text text-gray-400">Message</span></label>
                            <textarea wire:model="message" class="textarea bg-slate-50 dark:bg-white/5 border border-slate-300 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-500 focus:outline-none focus:border-blue-600 dark:focus:border-blue-500 rounded-xl h-32 outline-none" placeholder="How can we assist you?"></textarea>
                        </div>

                        <button type="submit" class="btn btn-block bg-blue-600 hover:bg-blue-500 text-white border-none rounded-xl h-14 font-bold text-lg shadow-lg shadow-blue-900/20">
                            <span wire:loading.remove>Send Secure Message</span>
                            <span wire:loading class="loading loading-spinner"></span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>
</div>
</div>
