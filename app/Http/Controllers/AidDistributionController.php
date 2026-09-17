<?php

namespace App\Http\Controllers;

use App\Http\Requests\AidDistributionRequest;
use App\Models\AidDistribution;
use App\Models\AuditLog;
use App\Models\Beneficiary;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AidDistributionController extends Controller
{
    public function index(Request $request)
    {
        $query = AidDistribution::with(['family', 'beneficiary']);

        if ($search = $request->input('search')) {
            $query->where(function ($builder) use ($search) {
                $builder->whereLike('aid_type', "%{$search}%", caseSensitive: false)
                    ->orWhereLike('reference_number', "%{$search}%", caseSensitive: false)
                    ->orWhereHas('family', fn ($family) => $family->whereLike('case_number', "%{$search}%", caseSensitive: false));
            });
        }

        return view('aid.index', ['distributions' => $query->latest('distribution_date')->paginate(15)->withQueryString()]);
    }

    public function create()
    {
        return view('aid.form', [
            'distribution' => new AidDistribution(['distribution_date' => now()->toDateString()]),
            'families' => Family::orderBy('case_number')->get(),
            'beneficiaries' => Beneficiary::orderBy('full_name')->get(),
        ]);
    }

    public function store(AidDistributionRequest $request)
    {
        $data = $this->validated($request);
        $data['created_by'] = auth()->id();
        $distribution = DB::transaction(function () use ($data, $request) {
            $distribution = AidDistribution::create($data);
            $this->audit($request, 'created', $distribution, $data);

            return $distribution;
        });

        return redirect('/aid-distributions')->with('status', __('messages.aid.created'));
    }

    public function show(AidDistribution $aidDistribution)
    {
        return view('aid.show', ['distribution' => $aidDistribution->load(['family', 'beneficiary'])]);
    }

    public function edit(AidDistribution $aidDistribution)
    {
        return view('aid.form', [
            'distribution' => $aidDistribution,
            'families' => Family::orderBy('case_number')->get(),
            'beneficiaries' => Beneficiary::orderBy('full_name')->get(),
        ]);
    }

    public function update(AidDistributionRequest $request, AidDistribution $aidDistribution)
    {
        $data = $this->validated($request);
        $data['updated_by'] = auth()->id();
        DB::transaction(function () use ($data, $aidDistribution, $request): void {
            $aidDistribution->update($data);
            $this->audit($request, 'updated', $aidDistribution, $data);
        });

        return redirect('/aid-distributions/'.$aidDistribution->id)->with('status', __('messages.aid.updated'));
    }

    public function destroy(AidDistribution $aidDistribution)
    {
        $aidDistribution->delete();

        return redirect('/aid-distributions')->with('status', __('messages.aid.removed'));
    }

    private function validated(AidDistributionRequest $request): array
    {
        return $request->validated();
    }

    private function audit(Request $request, string $action, AidDistribution $distribution, array $data): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => 'AidDistribution',
            'model_id' => $distribution->id,
            'description' => "Aid distribution {$action}",
            'new_values' => $data,
            'ip_address' => $request->ip(),
        ]);
    }
}
