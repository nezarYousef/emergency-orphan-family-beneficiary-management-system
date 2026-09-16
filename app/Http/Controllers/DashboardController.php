<?php

namespace App\Http\Controllers;

use App\Models\AidDistribution;
use App\Models\Beneficiary;
use App\Models\Family;
use App\Models\Orphan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard', [
            'families' => Family::count(),
            'beneficiaries' => Beneficiary::count(),
            'orphans' => Orphan::count(),
            'withoutProvider' => Family::whereIn('provider_status', ['no_provider', 'deceased_provider', 'missing_provider', 'disabled_provider'])->count(),
            'aidThisMonth' => AidDistribution::whereBetween('distribution_date', [now()->startOfMonth(), now()->endOfMonth()])->count(),
            'activeSponsoredOrphans' => Orphan::where('orphan_status', 'active')->where('sponsorship_status', 'sponsored')->count(),
            'recentFamilies' => Family::latest()->take(5)->get(),
            'recentAid' => AidDistribution::with('family')->latest('distribution_date')->take(5)->get(),
            'familiesByRegion' => Family::query()->select('governorate', DB::raw('count(*) as total'))->groupBy('governorate')->orderByDesc('total')->get(),
        ]);
    }
}
