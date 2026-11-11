<?php

namespace App\Http\Controllers;

use App\Services\CinetPayGateway;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CinetPayController extends Controller
{
    /** Notification de CinetPay. Le contenu sert uniquement à retrouver la transaction. */
    public function __invoke(Request $request, CinetPayGateway $gateway): Response
    {
        $transactionId = (string) ($request->input('transaction_id') ?? $request->input('cpm_trans_id', ''));

        if ($transactionId !== '') {
            $gateway->sync($transactionId);
        }

        return response('OK', 200);
    }
}