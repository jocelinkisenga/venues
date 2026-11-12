<?php

namespace App\Support;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;

final class Navigation
{
    /** @return array<int, array{title: ?string, items: array}> */
    public static function sidebar(User $user): array
    {
        $owner = $user->role === Role::Owner;
        $sales = $user->role->canSell();

        $sections = [
            ['title' => null, 'items' => [
                self::item('Tableau de bord', 'dashboard', 'home', 'dashboard'),
                self::item('Calendrier', 'calendar', 'calendar', 'calendar'),
                $sales ? self::item('Nouvelle réservation', 'bookings.create', 'plus', 'bookings.create') : null,
            ]],
            ['title' => 'Commercial', 'items' => [
                $sales ? self::item('Pipeline CRM', 'crm', 'funnel', 'crm') : null,
            ]],
            ['title' => 'Exploitation', 'items' => [
                self::item('Stock matériel', 'stock', 'cube', 'stock'),
            ]],
            ['title' => 'Finances', 'items' => $owner ? [
                self::item('Rentabilité', 'finance.profit', 'chart', 'finance.profit'),
                self::item('Dépenses', 'finance.expenses', 'banknotes', 'finance.expenses'),
                self::item('Tarifs saisonniers', 'pricing.seasons', 'tag', 'pricing.seasons'),
            ] : []],
            ['title' => 'Administration', 'items' => $owner ? [
                self::item('Catalogue', 'catalog', 'clipboard', 'catalog'),
                self::item('Journal d\'audit', 'audit', 'shield', 'audit'),
                self::item('Accès API', 'settings.api', 'key', 'settings.api'),
                self::item('Données personnelles', 'privacy', 'shield', 'privacy'),
                self::item('Équipe', 'team', 'users', 'team'),
                self::item('Profil public', 'venue.profile', 'building', 'venue.profile'),
                
            ] : []],
            ['title' => 'Compte', 'items' => array_merge(
                [self::item('Mes salles', 'venues', 'building', 'venues')],
                $user->is_super_admin ? [self::item('Plateforme', 'platform', 'globe', 'platform')] : [],
            )],
        ];

        return self::clean($sections);
    }

    /** Quatre raccourcis pour la barre inférieure mobile. */
    public static function mobile(User $user): array
    {
        $sales = $user->role->canSell();

        $items = [
            self::item('Accueil', 'dashboard', 'home', 'dashboard'),
            self::item('Calendrier', 'calendar', 'calendar', 'calendar'),
            $sales
                ? self::item('Réserver', 'bookings.create', 'plus', 'bookings.create')
                : self::item('Stock', 'stock', 'cube', 'stock'),
        ];

        return array_values(array_filter($items, fn ($i) => $i && Route::has($i['route'])));
    }

    private static function item(string $label, string $route, string $icon, string $match): array
    {
        return ['label' => $label, 'route' => $route, 'icon' => $icon, 'match' => $match];
    }

    /** Retire les éléments nuls et les routes absentes, puis les sections vides. */
    private static function clean(array $sections): array
    {
        $result = [];

        foreach ($sections as $section) {
            $items = array_values(array_filter(
                $section['items'],
                fn ($i) => $i && Route::has($i['route']),
            ));

            if ($items) {
                $result[] = ['title' => $section['title'], 'items' => $items];
            }
        }

        return $result;
    }
}