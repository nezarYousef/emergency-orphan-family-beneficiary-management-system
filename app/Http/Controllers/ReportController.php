<?php

namespace App\Http\Controllers;

use App\Models\AidDistribution;
use App\Models\Family;
use App\Models\Orphan;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __invoke()
    {
        return view('reports.index', ['governorates' => DB::table('families')->leftJoin('beneficiaries', 'families.id', '=', 'beneficiaries.family_id')->select('governorate', DB::raw('count(distinct families.id) families'), DB::raw('count(beneficiaries.id) beneficiaries'))->groupBy('governorate')->get(), 'sponsorship' => Orphan::select('sponsorship_status', DB::raw('count(*) total'))->groupBy('sponsorship_status')->get(), 'vulnerability' => Family::select('vulnerability_status', DB::raw('count(*) total'))->groupBy('vulnerability_status')->get(), 'aid' => AidDistribution::select('aid_type', DB::raw('count(*) total'), DB::raw('coalesce(sum(amount),0) amount'))->groupBy('aid_type')->get()]);
    }
}
