<?php
namespace App\Http\Controllers;
use App\Models\{Family,Beneficiary,Orphan,AidDistribution};
class DashboardController extends Controller { public function __invoke(){return view('dashboard',['families'=>Family::count(),'beneficiaries'=>Beneficiary::count(),'orphans'=>Orphan::count(),'withoutProvider'=>Family::whereIn('provider_status',['no_provider','deceased_provider','missing_provider','disabled_provider'])->count(),'recentFamilies'=>Family::latest()->take(5)->get(),'recentAid'=>AidDistribution::with('family')->latest('distribution_date')->take(5)->get()]);} }
