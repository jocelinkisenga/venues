<div class="contract">
    <h2>Contrat de location · {{ $booking->reference }}</h2>

    <p><strong>Entre :</strong> {{ $venue->name }}@if($venue->nif), NIF {{ $venue->nif }}@endif,
        {{ $venue->address ?? $venue->city }} (ci-après « la Salle »),</p>
    <p><strong>Et :</strong> {{ $booking->client_name }}, téléphone {{ $booking->client_phone }} (ci-après « le Client »).</p>

    <h3>1. Objet</h3>
    <p>Mise à disposition de <strong>{{ $booking->space->name }}</strong> pour un(e) {{ $booking->event_type }},
        du {{ $booking->starts_at->format('d/m/Y à H:i') }} au {{ $booking->ends_at->format('d/m/Y à H:i') }},
        pour {{ $booking->guests_count }} invités au maximum.</p>

    <h3>2. Prix et paiement</h3>
    <table>
        <thead><tr><th>Prestation</th><th style="text-align:right">Montant</th></tr></thead>
        <tbody>
            @foreach($quote->lines as $line)
                <tr>
                    <td>{{ $line->label }}</td>
                    <td style="text-align:right">{{ $venue->money($line->line_total) }}</td>
                </tr>
            @endforeach
            @if($quote->discount_amount > 0)
                <tr><td>Remise ({{ $quote->discount_reason }})</td><td style="text-align:right">- {{ $venue->money($quote->discount_amount) }}</td></tr>
            @endif
            <tr><td>TVA</td><td style="text-align:right">{{ $venue->money($quote->tax_amount) }}</td></tr>
            <tr><td><strong>Total TTC</strong></td><td style="text-align:right"><strong>{{ $venue->money($quote->total) }}</strong></td></tr>
        </tbody>
    </table>
    <p>Acompte de {{ $venue->deposit_percent }} % à la signature, solde avant l'événement.
        Modes de paiement : Mobile Money, carte bancaire, virement, espèces.</p>

    <h3>3. Annulation</h3>
    <ul>
        @foreach($policy as $rule)
            <li>Annulation à {{ $rule['days'] > 0 ? "partir de {$rule['days']} jours" : 'moins de 15 jours' }} avant l'événement :
                {{ $rule['refund'] }} % des sommes versées remboursé.</li>
        @endforeach
    </ul>

    <h3>4. Obligations de la Salle et du Client</h3>
    <ul>
        <li>Le Client respecte la capacité maximale et les horaires convenus.</li>
        <li>Les nuisances sonores doivent cesser à l'heure fixée par la Salle.</li>
        <li>Un état des lieux d'entrée et un état des lieux de sortie sont établis contradictoirement.</li>
        <li>Les dégâts constatés sont imputés sur le dépôt de garantie, puis, si besoin, facturés au Client.</li>
    </ul>

    <h3>5. Signature</h3>
    <p>Le présent contrat est signé électroniquement par le Client, en fin de document.</p>
</div>