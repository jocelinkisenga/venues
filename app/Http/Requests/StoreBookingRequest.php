<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role?->canSell() ?? false;
    }

    public function rules(): array
    {
        $venueId = $this->user()?->venue_id;

        return [
            'space_id' => ['required', Rule::exists('spaces', 'id')->where('venue_id', $venueId)],
            'package_id' => ['nullable', Rule::exists('packages', 'id')->where('venue_id', $venueId)],
            'lead_id' => ['nullable', Rule::exists('leads', 'id')->where('venue_id', $venueId)],
            'event_type' => ['required', 'string', 'max:60'],
            'client_name' => ['required', 'string', 'max:120'],
            // Numéros locaux acceptés : +225 07..., 0022..., +237 6..., etc.
            'client_phone' => ['required', 'string', 'max:30', 'regex:/^\+?[0-9\s]{8,20}$/'],
            'guests_count' => ['required', 'integer', 'min:1', 'max:5000'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'option' => ['boolean'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
