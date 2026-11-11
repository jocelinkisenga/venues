<x-guest-layout>
<div class="bg-white text-gray-900 dark:bg-black dark:text-white min-h-screen pb-20">
    <section class="py-16 border-b border-green-500/10 text-center">
        <div class="max-w-4xl mx-auto px-6">
            <div class="inline-block p-3 bg-green-500/10 rounded-full text-green-500 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <h1 class="text-4xl font-extrabold dark:text-white">Privacy & Data Governance</h1>
            <p class="text-green-500 mt-2 font-mono uppercase tracking-tighter">Enterprise-Grade Protection</p>
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-6 mt-12 space-y-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white/5 border border-green-500/10 p-8 rounded-2xl backdrop-blur-xl">
                <h3 class="text-green-500 font-bold mb-4">Data Encryption</h3>
                <p class="dark:text-gray-400 text-sm">
                    All tax documents are stored on private, encrypted S3 buckets. We use AES-256 encryption at rest 
                    and TLS 1.3 for all data in transit.
                </p>
            </div>
            <div class="bg-white/5 border border-green-500/10 p-8 rounded-2xl backdrop-blur-xl">
                <h3 class="text-green-500 font-bold mb-4">AI Audit Logs</h3>
                <p class="dark:text-gray-400 text-sm">
                    We maintain a full journalization of AI outputs for auditability. Your raw financial data is 
                    never used to train public AI models.
                </p>
            </div>
        </div>

        <article class="prose prose-invert max-w-none text-gray-300">
            <h2 class="dark:text-white">Your Rights (GDPR/CCPA Compliance)</h2>
            <p>
                As an ILANDS AI user, you have the right to request a full export of your <code>tax_profiles</code> 
                and the immediate deletion of your encrypted documents from our secure storage.
            </p>
        </article>
    </div>
</div>
</x-guest-layout>