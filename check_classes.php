<?php

require __DIR__.'/vendor/autoload.php';

$classes = [
    // Livewire
    'App\Livewire\Admin\AuditTrail',
    'App\Livewire\Bookings\BookingForm',
    'App\Livewire\Bookings\CancelBooking',
    'App\Livewire\Bookings\DepositManager',
    'App\Livewire\Contracts\ContractManager',
    'App\Livewire\Crm\LeadPipeline',
    'App\Livewire\Finance\ExpenseManager',
    'App\Livewire\Finance\Profitability',
    'App\Livewire\Guests\CheckIn',
    'App\Livewire\Guests\GuestList',
    'App\Livewire\Inventory\StockManager',
    'App\Livewire\Operations\BeoView',
    'App\Livewire\Operations\BookingLogistics',
    'App\Livewire\Operations\ChecklistRunner',
    'App\Livewire\Operations\StaffAssignments',
    'App\Livewire\Platform\PlatformDashboard',
    'App\Livewire\Planning\Calendar',
    'App\Livewire\Planning\SeatingPlanner',
    'App\Livewire\Pricing\SeasonRules',
    'App\Livewire\Quotes\QuoteConfigurator',
    'App\Livewire\Venues\MyVenues',
    // Contrôleurs
    'App\Http\Controllers\CinetPayController',
    'App\Http\Controllers\ContractPortalController',
    'App\Http\Controllers\DocumentController',
    'App\Http\Controllers\PortalController',
    'App\Http\Controllers\RsvpController',
    // Commandes
    'App\Console\Commands\CheckPendingMobilePayments',
    'App\Console\Commands\MakeSuperAdmin',
    'App\Console\Commands\SendBookingReminders',
];

foreach ($classes as $class) {
    echo str_pad($class, 60), class_exists($class) ? 'OK' : 'MANQUANT', PHP_EOL;
}