<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Services\VenueSwitcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VenueSwitchController extends Controller
{
    public function __invoke(Request $request, Venue $venue, VenueSwitcher $switcher): RedirectResponse
    {
        try {
            $switcher->switchTo($request->user(), $venue);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('dashboard')->with('success', "Salle ouverte : {$venue->name}");
    }
}