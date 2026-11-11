<?php

namespace App\Services;

use App\Models\Booking;

/**
 * Banquet Event Order : feuille de route heure par heure pour l'équipe technique et le ménage.
 */
class BeoGenerator
{
    /** @return array<int, array{time: string, task: string, team: string}> */
    public function build(Booking $booking): array
    {
        $start = $booking->starts_at;
        $end = $booking->ends_at;

        $rows = [
            [$start->copy()->subHours(3), 'Arrivée de l\'équipe technique et du ménage', 'Technique'],
            [$start->copy()->subHours(2), 'Installation des tables, chaises et sonorisation', 'Technique'],
            [$start->copy()->subHour(), 'Contrôle final : sono, éclairage, état des lieux d\'entrée', 'Technique'],
            [$start->copy(), 'Accueil des invités', 'Accueil'],
            [$start->copy()->addHour(), 'Début du service / animation', 'Salle'],
            [$end->copy(), 'Fin de l\'événement', 'Salle'],
            [$end->copy()->addMinutes(30), 'Démontage et restitution du matériel', 'Technique'],
            [$end->copy()->addHour(), 'Nettoyage et état des lieux de sortie', 'Ménage'],
        ];

        // Multi-jours : une ligne par journée intermédiaire
        $days = (int) $start->copy()->startOfDay()->diffInDays($end->copy()->startOfDay());
        for ($d = 1; $d < $days; $d++) {
            $rows[] = [$start->copy()->startOfDay()->addDays($d)->setTime(9, 0), 'Jour '.($d + 1).' : programme du jour', 'Salle'];
        }

        usort($rows, fn ($a, $b) => $a[0] <=> $b[0]);

        return array_map(fn ($r) => [
            'time' => $r[0]->format('d/m H:i'),
            'task' => $r[1],
            'team' => $r[2],
        ], $rows);
    }
}
