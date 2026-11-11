<?php 
namespace App\Services;

use App\Models\TaxProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class TaxProfileService
{
    public function getAllTaxProfiles () {
        return TaxProfile::where('user_id',auth()->user()->id)->latest()->get();
    }


    /**
     * Enregistrer un nouveau profil fiscal.
     */
    public function register(array $data)
    {

    $profile = TaxProfile::create($data);

    $this->logAction('create', $profile);
    
    return $profile;

    }

    /**
     * Mettre à jour un profil existant.
     */
    public function update(TaxProfile $profile, array $data): bool
    {
        return DB::transaction(function () use ($profile, $data) {
            $updated = $profile->update($data);

            if ($updated) {
                $this->logAction('update', $profile);
            }

            return $updated;
        });
    }

    /**
     * Annuler (Supprimer) un profil fiscal.
     */
    public function cancel(TaxProfile $profile): bool
    {
        return DB::transaction(function () use ($profile) {
            $this->logAction('delete', $profile);
            
            return $profile->delete();
        });
    }

    /**
     * Journalisation interne pour l'auditabilité (Gouvernance IA).
     * 
     */
    protected function logAction(string $action, TaxProfile $profile): void
    {
        Log::info("Tax Profile {$action}", [
            'user_id' => auth()->id() ?? $profile->user_id,
            'profile_id' => $profile->id,
            'timestamp' => now()->toIso8601String(),
            'ip' => request()->ip()
        ]);
        
        // Enregistrer dans une 
    }
}