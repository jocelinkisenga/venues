<?php

namespace App\Livewire\Privacy;

use App\Enums\Role;
use App\Services\PrivacyService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PrivacyCenter extends Component
{
    public string $phone = '';
    public ?array $found = null;

    public function mount(): void
    {
        abort_unless(Auth::user()->role === Role::Owner, 403);
    }

    public function search(PrivacyService $privacy): void
    {
        $this->validatePhone();

        try {
            $this->found = $privacy->summary(Auth::user()->venue, $this->phone);
        } catch (\DomainException $e) {
            $this->addError('phone', $e->getMessage());
        }
    }

    public function export(PrivacyService $privacy)
    {
        $this->validatePhone();

        try {
            $data = $privacy->export(Auth::user()->venue, Auth::user(), $this->phone);
        } catch (\DomainException $e) {
            $this->addError('phone', $e->getMessage());

            return;
        }

        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }, 'donnees-personnelles-'.now()->format('Ymd-His').'.json', ['Content-Type' => 'application/json']);
    }

    public function anonymize(PrivacyService $privacy): void
    {
        $this->validatePhone();

        try {
            $count = $privacy->anonymize(Auth::user()->venue, Auth::user(), $this->phone);
        } catch (\DomainException $e) {
            $this->addError('phone', $e->getMessage());

            return;
        }

        session()->flash('success', "{$count} enregistrement(s) anonymisé(s). Paiements et contrats sont conservés.");
        $this->found = null;
        $this->reset('phone');
    }

    private function validatePhone(): void
    {
        $this->validate(['phone' => ['required', 'string', 'max:30']]);
    }

    public function render()
    {
        return view('livewire.privacy.privacy-center')->layout('layouts.app');
    }
}