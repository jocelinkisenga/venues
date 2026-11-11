<?php

use App\Http\Controllers\Api\V1\AvailabilityController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\CheckinSyncController;
use App\Http\Controllers\Api\V1\GuestListController;
use App\Http\Controllers\Api\V1\SpaceController;
use App\Support\ApiAbilities;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/spaces', SpaceController::class)
        ->middleware('abilities:'.ApiAbilities::SPACES_READ);

    Route::get('/availability', AvailabilityController::class)
        ->middleware('abilities:'.ApiAbilities::AVAILABILITY_READ);

    Route::get('/bookings', BookingController::class)
        ->middleware('abilities:'.ApiAbilities::BOOKINGS_READ);

    Route::get('/bookings/{booking}/guests', GuestListController::class)
        ->middleware('abilities:'.ApiAbilities::GUESTS_READ);

    Route::post('/checkins/sync', CheckinSyncController::class)
        ->middleware('abilities:'.ApiAbilities::CHECKINS_WRITE);
});