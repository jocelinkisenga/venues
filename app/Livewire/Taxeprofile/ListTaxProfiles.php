<?php

namespace App\Livewire\Taxeprofile;

use App\Models\TaxProfile;
use App\Services\TaxProfileService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.client')]
class ListTaxProfiles extends Component
{
    /**
     * Delete one of the current user's profiles.
     * The lookup is scoped to the owner, so another user's id gives a 404.
     */
    public function delete(int $id, TaxProfileService $service): void
    {
        $profile = TaxProfile::where('user_id', auth()->id())->findOrFail($id);

        try {
            $service->cancel($profile);

            session()->flash('message', 'Tax profile deleted.');
        } catch (\Throwable $e) {
            report($e);
            $this->addError('delete_error', 'We could not delete this profile. Please try again.');
        }
    }

    public function render(TaxProfileService $service)
    {
        return view('livewire.taxeprofile.list-tax-profiles', [
            'profiles' => $service->getAllTaxProfiles(),
        ]);
    }
}
