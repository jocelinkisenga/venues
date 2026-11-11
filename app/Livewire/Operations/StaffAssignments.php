<?php

namespace App\Livewire\Operations;

use App\Models\Assignment;
use App\Models\Booking;
use App\Models\StaffMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class StaffAssignments extends Component
{
    public const ROLES = [
        'technique' => 'Technique', 'menage' => 'Ménage', 'service' => 'Service',
        'accueil' => 'Accueil', 'securite' => 'Sécurité',
    ];

    public Booking $booking;

    public string $memberName = '';
    public string $memberRole = 'service';
    public string $memberPhone = '';
    public string $memberRate = '0';

    public ?int $staffId = null;
    public string $assignRole = 'service';
    public string $assignPay = '0';

    public function mount(Booking $booking): void
    {
        abort_unless($booking->venue_id === Auth::user()->venue_id, 404);
        abort_unless(Auth::user()->role->canSell(), 403);

        $this->booking = $booking;
    }

    public function addMember(): void
    {
        $data = $this->validate([
            'memberName' => ['required', 'string', 'max:120'],
            'memberRole' => ['required', Rule::in(array_keys(self::ROLES))],
            'memberPhone' => ['nullable', 'string', 'max:30'],
            'memberRate' => ['required', 'numeric', 'min:0'],
        ]);

        StaffMember::create([
            'venue_id' => Auth::user()->venue_id,
            'name' => $data['memberName'],
            'role' => $data['memberRole'],
            'phone' => $data['memberPhone'] ?: null,
            'default_rate' => $data['memberRate'],
        ]);

        $this->reset('memberName', 'memberPhone');
    }

    public function assign(): void
    {
        $data = $this->validate([
            'staffId' => ['required', Rule::exists('staff_members', 'id')->where('venue_id', Auth::user()->venue_id)],
            'assignRole' => ['required', Rule::in(array_keys(self::ROLES))],
            'assignPay' => ['required', 'numeric', 'min:0'],
        ]);

        Assignment::updateOrCreate(
            ['booking_id' => $this->booking->id, 'staff_member_id' => $data['staffId']],
            [
                'venue_id' => $this->booking->venue_id,
                'role' => $data['assignRole'],
                'pay_amount' => $data['assignPay'],
            ],
        );

        $this->reset('staffId');
        $this->assignPay = '0';
    }

    public function togglePaid(int $assignmentId): void
    {
        $assignment = Assignment::where('booking_id', $this->booking->id)->findOrFail($assignmentId);
        $assignment->update(['paid_at' => $assignment->paid_at ? null : now()]);
    }

    public function remove(int $assignmentId): void
    {
        Assignment::where('booking_id', $this->booking->id)->findOrFail($assignmentId)->delete();
    }

    public function render()
    {
        $assignments = Assignment::with('staffMember')->where('booking_id', $this->booking->id)->get();

        return view('livewire.operations.staff-assignments', [
            'members' => StaffMember::where('venue_id', $this->booking->venue_id)->orderBy('name')->get(),
            'assignments' => $assignments,
            'totalPay' => (float) $assignments->sum('pay_amount'),
            'roles' => self::ROLES,
            'venue' => $this->booking->venue,
        ])->layout('layouts.app');
    }
}