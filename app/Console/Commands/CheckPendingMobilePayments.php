<?php

namespace App\Console\Commands;

use App\Models\GatewayIntent;
use App\Services\CinetPayGateway;
use Illuminate\Console\Command;

class CheckPendingMobilePayments extends Command
{
    protected $signature = 'payments:check-pending';

    protected $description = 'Vérifie auprès de CinetPay les paiements Mobile Money restés en attente.';

    public function handle(CinetPayGateway $gateway): int
    {
        GatewayIntent::where('provider', 'cinetpay')
            ->where('status', 'pending')
            ->whereBetween('created_at', [now()->subDay(), now()->subMinutes(2)])
            ->pluck('transaction_id')
            ->each(fn (string $id) => $gateway->sync($id));

        return self::SUCCESS;
    }
}