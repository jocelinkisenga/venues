@php
    $card = 'rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60';
@endphp

<div class="mx-auto max-w-3xl space-y-5">
    <div>
        <a href="{{ route('bookings.quote', $booking) }}" class="text-sm text-slate-500 hover:underline">← Devis</a>
        <h1 class="text-2xl font-bold">Contrat · {{ $booking->reference }}</h1>
    </div>

    @error('contract') <p class="rounded-xl bg-red-50 p-3 text-sm text-red-600">{{ $message }}</p> @enderror

    <div class="{{ $card }} space-y-3">
        @if(! $contract)
            <p class="text-sm text-slate-500">Aucun contrat pour l'instant. Il est généré à partir du devis actuel.</p>
            <button wire:click="issue" class="rounded-xl bg-amber-500 px-4 py-2 font-semibold text-white hover:bg-amber-600">Générer le contrat</button>
        @else
            <p class="text-sm">
                N° <strong>{{ $contract->number }}</strong> ·
                <span class="font-semibold {{ $contract->status === 'signed' ? 'text-emerald-600' : 'text-amber-600' }}">
                    {{ ['draft' => 'Brouillon', 'sent' => 'Envoyé, en attente de signature', 'signed' => 'Signé'][$contract->status] }}
                </span>
            </p>

            <div class="flex flex-wrap gap-2 text-sm">
                <a href="{{ route('pdf.contract', $contract) }}" class="rounded-lg bg-white px-3 py-2 shadow-sm dark:bg-slate-800">Télécharger le PDF</a>
                @if($contract->status !== 'signed')
                    <button onclick="navigator.clipboard.writeText('{{ $signUrl }}')" class="rounded-lg bg-slate-800 px-3 py-2 text-white dark:bg-slate-200 dark:text-slate-900">Copier le lien de signature</button>
                    <a href="https://wa.me/{{ ltrim(preg_replace('/\D/', '', $booking->client_phone), '+') }}?text={{ urlencode('Bonjour, voici votre contrat à lire et signer : '.$signUrl) }}"
                       target="_blank" wire:click="markSent" class="rounded-lg bg-emerald-600 px-3 py-2 font-medium text-white">Envoyer par WhatsApp</a>
                @endif
            </div>

            @if($contract->status === 'draft')
                <button wire:click="issue" wire:confirm="Régénérer le contrat avec le devis actuel ?" class="text-sm text-slate-500 hover:underline">Régénérer depuis le devis</button>
            @endif

            @if($contract->signed_at)
                <p class="text-sm text-slate-500">Signé par {{ $contract->signed_name }} le {{ $contract->signed_at->format('d/m/Y à H:i') }}.</p>
            @endif
        @endif
    </div>

    @if($contract)
        <div class="{{ $card }} prose-sm max-w-none text-sm">{!! $contract->body !!}</div>
    @endif
</div>