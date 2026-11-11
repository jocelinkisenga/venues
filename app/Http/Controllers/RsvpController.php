<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Services\GuestService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RsvpController extends Controller
{
    public function show(string $token)
    {
        $guest = Guest::with('booking.venue')->where('token', $token)->firstOrFail();

        return view('rsvp.show', compact('guest'));
    }

    public function store(Request $request, string $token, GuestService $guests)
    {
        $guest = Guest::where('token', $token)->firstOrFail();

        $data = $request->validate([
            'response' => ['required', Rule::in(['confirmed', 'declined'])],
            'companions' => ['nullable', 'integer', 'min:0', 'max:10'],
        ]);

        try {
            $guests->respond($guest, $data['response'], (int) ($data['companions'] ?? 0));
        } catch (\DomainException $e) {
            return back()->withErrors(['response' => $e->getMessage()]);
        }

        return redirect()->route('rsvp.show', $token);
    }
}