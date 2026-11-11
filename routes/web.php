<?php

use App\Http\Controllers\CinetPayController;
use App\Http\Controllers\ContractPortalController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\RsvpController;
use App\Livewire\Admin\AuditTrail;
use App\Livewire\Bookings\BookingForm;
use App\Livewire\Bookings\CancelBooking;
use App\Livewire\Bookings\DepositManager;
use App\Livewire\Contracts\ContractManager;
use App\Livewire\Crm\LeadPipeline;
use App\Livewire\Finance\ExpenseManager;
use App\Livewire\Finance\Profitability;
use App\Livewire\Guests\CheckIn;
use App\Livewire\Guests\GuestList;
use App\Livewire\Inventory\StockManager;
use App\Livewire\Operations\BeoView;
use App\Livewire\Operations\BookingLogistics;
use App\Livewire\Operations\ChecklistRunner;
use App\Livewire\Operations\StaffAssignments;
use App\Livewire\Platform\PlatformDashboard;
use App\Livewire\Planning\Calendar;
use App\Livewire\Planning\SeatingPlanner;
use App\Livewire\Pricing\SeasonRules;
use App\Livewire\Quotes\QuoteConfigurator;
use App\Livewire\Venues\MyVenues;
use App\Models\Booking;
use App\Livewire\Catalog\CatalogManager;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

/*
|--------------------------------------------------------------------------
| Accès public, sans compte
|--------------------------------------------------------------------------
*/

// Mobile Money : CinetPay notifie, l'application vérifie auprès de l'API
Route::post('/cinetpay/notify', CinetPayController::class)->name('cinetpay.notify');

// Espace client : liens signés
Route::middleware('signed')->group(function () {
    Route::get('/espace-client/{booking}', [PortalController::class, 'show'])->name('portal.show');
    Route::post('/espace-client/{booking}/payer', [PortalController::class, 'pay'])->name('portal.pay');
    Route::match(['get', 'post'], '/contrat/{contract}', [ContractPortalController::class, 'handle'])
        ->name('portal.contract');
});

Route::view('/paiement/merci', 'portal.thanks')->name('portal.thanks');
Route::view('/paiement/annule', 'portal.cancelled')->name('portal.cancelled');

// RSVP : le jeton aléatoire de l'invité sert de secret
Route::get('/rsvp/{token}', [RsvpController::class, 'show'])->name('rsvp.show');
Route::post('/rsvp/{token}', [RsvpController::class, 'store'])->name('rsvp.store');

/*
|--------------------------------------------------------------------------
| Authentifié, sans contrainte de salle active
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Doit rester accessible même si la salle active est suspendue
    Route::get('/salles', MyVenues::class)->name('venues');
});

Route::middleware(['auth', 'verified', 'superadmin'])->group(function () {
    Route::get('/plateforme', PlatformDashboard::class)->name('platform');
});

/*
|--------------------------------------------------------------------------
| Authentifié, salle active non suspendue
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'venue.active'])->group(function () {

    Route::get('/dashboard', function () {
        $venueId = auth()->user()->venue_id;

        return view('dashboard', [
            'upcoming' => Booking::where('venue_id', $venueId)
                ->where('starts_at', '>=', now())
                ->whereIn('status', ['option', 'confirmed', 'in_progress'])
                ->with('space')->orderBy('starts_at')->limit(10)->get(),
            'expiringOptions' => Booking::where('venue_id', $venueId)
                ->where('status', 'option')
                ->where('option_expires_at', '>', now())
                ->with('space')->orderBy('option_expires_at')->limit(5)->get(),
        ]);
    })->name('dashboard');

    // Lecture pour toute l'équipe ; le déplacement est contrôlé dans le composant
    Route::get('/calendrier', Calendar::class)->name('calendar');
    Route::get('/stock', StockManager::class)->name('stock');
    Route::get('/bookings/{booking}/plan', SeatingPlanner::class)->name('bookings.seating');
    Route::get('/bookings/{booking}/beo', BeoView::class)->name('bookings.beo');
    Route::get('/bookings/{booking}/pdf/beo', [DocumentController::class, 'beo'])->name('pdf.beo');
    Route::get('/bookings/{booking}/checklist/{phase}', ChecklistRunner::class)
        ->where('phase', 'entry|exit')
        ->name('bookings.checklist');
    Route::get('/bookings/{booking}/logistique', BookingLogistics::class)->name('bookings.logistics');

    // Entrée à l'événement : toute l'équipe, y compris sécurité et ménage
    Route::get('/bookings/{booking}/entree', CheckIn::class)->name('bookings.checkin');

    // Ventes : propriétaire et commercial
    Route::middleware('role:proprietaire,commercial')->group(function () {
        Route::get('/bookings/nouveau', BookingForm::class)->name('bookings.create');
        Route::get('/bookings/{booking}/devis', QuoteConfigurator::class)->name('bookings.quote');
        Route::get('/bookings/{booking}/annulation', CancelBooking::class)->name('bookings.cancel');
        Route::get('/bookings/{booking}/caution', DepositManager::class)->name('bookings.deposit');
        Route::get('/bookings/{booking}/equipe', StaffAssignments::class)->name('bookings.staff');
        Route::get('/bookings/{booking}/contrat', ContractManager::class)->name('bookings.contract');
        Route::get('/bookings/{booking}/invites', GuestList::class)->name('bookings.guests');
        Route::get('/crm', LeadPipeline::class)->name('crm');
        Route::get('/bookings/{booking}/pdf/devis', [DocumentController::class, 'quote'])->name('pdf.quote');
        Route::get('/paiements/{payment}/recu', [DocumentController::class, 'receipt'])->name('pdf.receipt');
        Route::get('/contrats/{contract}/pdf', [DocumentController::class, 'contract'])->name('pdf.contract');
    });

    // Propriétaire uniquement
    Route::middleware('role:proprietaire')->group(function () {
        Route::get('/journal', AuditTrail::class)->name('audit');
        Route::get('/finances/depenses', ExpenseManager::class)->name('finance.expenses');
        Route::get('/finances/rentabilite', Profitability::class)->name('finance.profit');
        Route::get('/tarifs/saisons', SeasonRules::class)->name('pricing.seasons');
        Route::get('/catalogue', CatalogManager::class)->name('catalog');
    });
});

require __DIR__ . "/auth.php";