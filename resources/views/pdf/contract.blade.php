<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
    h2 { font-size: 16px; } h3 { font-size: 12px; margin: 14px 0 4px; }
    table { width: 100%; border-collapse: collapse; margin: 6px 0; }
    th, td { border-bottom: 1px solid #e2e8f0; padding: 4px; text-align: left; }
    .sign { margin-top: 24px; border: 1px solid #cbd5e1; padding: 10px; }
    .muted { color: #64748b; font-size: 9px; }
</style>
</head>
<body>
    <p class="muted">Contrat n° {{ $contract->number }}</p>
    {!! $contract->body !!}

    @if($contract->status === 'signed')
        <div class="sign">
            <strong>Signé électroniquement</strong><br>
            Par : {{ $contract->signed_name }}<br>
            Le : {{ $contract->signed_at->format('d/m/Y à H:i') }} · IP : {{ $contract->signed_ip }}<br>
            <span class="muted">Empreinte du document (SHA-256) : {{ $contract->document_hash }}</span>
        </div>
    @endif
</body>
</html>