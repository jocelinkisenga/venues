<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeSuperAdmin extends Command
{
    protected $signature = 'platform:make-admin {email}';

    protected $description = 'Donne les droits de super-administration à un utilisateur existant.';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->firstOrFail();
        $user->update(['is_super_admin' => true]);

        $this->info("{$user->email} est maintenant super-administrateur.");

        return self::SUCCESS;
    }
}