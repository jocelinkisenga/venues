<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AuditTrail extends Component
{
    public function render()
    {
        return view('livewire.admin.audit-trail', [
            'logs' => AuditLog::with('user')
                ->where('venue_id', Auth::user()->venue_id)
                ->latest('id')
                ->limit(200)
                ->get(),
        ])->layout('layouts.app');
    }
}
