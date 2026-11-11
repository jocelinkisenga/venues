<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
    h1 { font-size: 18px; margin: 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border-bottom: 1px solid #e2e8f0; padding: 6px; text-align: left; }
    th { background: #f1f5f9; font-size: 10px; text-transform: uppercase; }
    .muted { color: #64748b; }
</style>
</head>
<body>
    <h1>BEO · {{ $booking->reference }}</h1>
    <p class="muted">{{ $venue->name }} · {{ $booking->event_type }} · {{ $booking->space->name }} · {{ $booking->guests_count }} invités</p>
    <p>{{ $booking->starts_at->format('d/m/Y H:i') }} → {{ $booking->ends_at->format('d/m/Y H:i') }}</p>

    <table>
        <thead><tr><th style="width: 18%">Heure</th><th>Tâche</th><th style="width: 16%">Équipe</th><th style="width: 8%">✓</th></tr></thead>
        <tbody>
            @foreach($rows as $row)
                <tr><td>{{ $row['time'] }}</td><td>{{ $row['task'] }}</td><td>{{ $row['team'] }}</td><td></td></tr>
            @endforeach
        </tbody>
    </table>

    @if($booking->notes)
        <p><strong>Notes :</strong> {{ $booking->notes }}</p>
    @endif
</body>
</html>
