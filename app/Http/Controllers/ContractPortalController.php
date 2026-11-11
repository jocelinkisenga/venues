<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Services\ContractService;
use Illuminate\Http\Request;

class ContractPortalController extends Controller
{
    /** GET : lecture du contrat. POST : signature. Même URL signée pour les deux. */
    public function handle(Request $request, Contract $contract, ContractService $service)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:120'],
                'accept' => ['accepted'],
            ]);

            try {
                $service->sign($contract, $data['name'], $request->ip(), $request->userAgent());
            } catch (\DomainException $e) {
                return back()->withErrors(['name' => $e->getMessage()])->withInput();
            }

            $service->markSent($contract);

            return redirect()->to($request->fullUrl())->with('success', 'Contrat signé. Merci.');
        }

        return view('portal.contract', compact('contract'));
    }
}