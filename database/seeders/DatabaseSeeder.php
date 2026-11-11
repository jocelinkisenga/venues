<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Package;
use App\Models\Partner;
use App\Models\Space;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $venue = Venue::create([
            'name' => 'Salle Prestige Plateau',
            'city' => 'Abidjan',
            'country' => 'CI',
            'currency' => 'XOF',
            'timezone' => 'Africa/Abidjan',
            'tax_rate' => 18,
            'deposit_percent' => 50,
        ]);

        app(\App\Services\CatalogService::class)->seedDefaults($venue);

        foreach ([
            ['Propriétaire', 'proprietaire@demo.test', Role::Owner],
            ['Commercial', 'commercial@demo.test', Role::Sales],
            ['Chef technique', 'technique@demo.test', Role::Technical],
        ] as [$name, $email, $role]) {
            User::create([
                'venue_id' => $venue->id,
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => $role,
            ]);
        }

        Space::insert([
            ['venue_id' => $venue->id, 'name' => 'Grande salle', 'zone' => 'grande_salle', 'capacity' => 300, 'created_at' => now(), 'updated_at' => now()],
            ['venue_id' => $venue->id, 'name' => 'Terrasse', 'zone' => 'terrasse', 'capacity' => 150, 'created_at' => now(), 'updated_at' => now()],
            ['venue_id' => $venue->id, 'name' => 'Espace VIP', 'zone' => 'vip', 'capacity' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['venue_id' => $venue->id, 'name' => 'Espace bar', 'zone' => 'bar', 'capacity' => 60, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Package::create([
            'venue_id' => $venue->id,
            'name' => 'Soirée location sèche',
            'type' => 'seche',
            'time_type' => 'soiree',
            'included_hours' => 8,
            'base_price' => 350000,
            'overtime_rate' => 25000,
            'includes' => ['Chaises', 'Tables rondes', 'Sono de base'],
        ]);

        Package::create([
            'venue_id' => $venue->id,
            'name' => 'Mariage clé en main (2 jours)',
            'type' => 'cle_en_main',
            'time_type' => 'multi_jours',
            'days' => 2,
            'included_hours' => 12,
            'base_price' => 2500000,
            'overtime_rate' => 40000,
            'includes' => ['Décoration', 'Sono & éclairage', 'Service traiteur'],
        ]);

        Partner::insert([
            ['venue_id' => $venue->id, 'name' => 'Traiteur Saveurs d\'Afrique', 'category' => 'traiteur', 'commission_percent' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['venue_id' => $venue->id, 'name' => 'Décoration Élégance', 'category' => 'decoration', 'commission_percent' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['venue_id' => $venue->id, 'name' => 'Boissons Premium', 'category' => 'boissons', 'commission_percent' => 8, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
