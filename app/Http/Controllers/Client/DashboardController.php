<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\TaxProfileService;
use Illuminate\Http\Request;
use App\Models\AiReport;
use App\Models\Chat;
use App\Models\Content;

class DashboardController extends Controller
{
        public function __construct(public TaxProfileService $tax_profile_service)
        {
                
        }
        public function index(Request $request)
{
            $contents = Content::latest()->limit(3)->get();


    $recentReports = AiReport::whereUser_id(auth()->user()->id)->latest()->take(5)->get();
    

    $user = auth()->user();

    if ($user->role === 'admin') {
        return view('admin.dashboard');
    }

    return view('client.dashboard', [
        'taxProfiles' => $this->tax_profile_service->getAllTaxProfiles()
    , "recentReports" =>$recentReports,
    "contents" => $contents
]);
}
}
