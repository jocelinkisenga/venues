<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\StripeWebhookController;
use App\Livewire\Admin\AuditTrail;
use App\Livewire\Bookings\BookingForm;
use App\Livewire\Bookings\CancelBooking;
use App\Livewire\Bookings\DepositManager;
use App\Livewire\Crm\LeadPipeline;
use App\Livewire\Operations\BeoView;
use App\Livewire\Operations\ChecklistRunner;
use App\Livewire\Planning\Calendar;
use App\Livewire\Planning\SeatingPlanner;
use App\Livewire\Quotes\QuoteConfigurator;
use App\Models\Booking;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

// ---- Accès public, sans compte ----
// Route::post('/stripe/webhook', StripeWebhookController::class)->name('stripe.webhook');

Route::middleware('signed')->group(function () {
    Route::get('/espace-client/{booking}', [PortalController::class, 'show'])->name('portal.show');
    Route::post('/espace-client/{booking}/payer', [PortalController::class, 'pay'])->name('portal.pay');
});

Route::view('/paiement/merci', 'portal.thanks')->name('portal.thanks');
Route::view('/paiement/annule', 'portal.cancelled')->name('portal.cancelled');

// ---- Équipe authentifiée ----
Route::middleware(['auth', 'verified'])->group(function () {
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

    Route::get('/calendrier', Calendar::class)->name('calendar');

    Route::middleware('role:proprietaire,commercial')->group(function () {
        Route::get('/bookings/nouveau', BookingForm::class)->name('bookings.create');
        Route::get('/bookings/{booking}/devis', QuoteConfigurator::class)->name('bookings.quote');
        Route::get('/bookings/{booking}/annulation', CancelBooking::class)->name('bookings.cancel');
        Route::get('/bookings/{booking}/caution', DepositManager::class)->name('bookings.deposit');
        Route::get('/crm', LeadPipeline::class)->name('crm');
        Route::get('/bookings/{booking}/pdf/devis', [DocumentController::class, 'quote'])->name('pdf.quote');
        Route::get('/paiements/{payment}/recu', [DocumentController::class, 'receipt'])->name('pdf.receipt');
    });

    Route::middleware('role:proprietaire')->group(function () {
        Route::get('/journal', AuditTrail::class)->name('audit');
    });

    Route::get('/bookings/{booking}/plan', SeatingPlanner::class)->name('bookings.seating');
    Route::get('/bookings/{booking}/beo', BeoView::class)->name('bookings.beo');
    Route::get('/bookings/{booking}/pdf/beo', [DocumentController::class, 'beo'])->name('pdf.beo');
    Route::get('/bookings/{booking}/checklist/{phase}', ChecklistRunner::class)
        ->where('phase', 'entry|exit')
        ->name('bookings.checklist');
});

require __DIR__.'/auth.php';