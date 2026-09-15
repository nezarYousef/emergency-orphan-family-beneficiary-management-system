<?php

namespace App\Http\Controllers;

use App\Models\AidDistribution;
use App\Models\AuditLog;
use App\Models\Beneficiary;
use App\Models\Family;
use Illuminate\Http\Request;

class AidDistributionController extends Controller
{
    public function index(Request $request)
    {
        $query = AidDistribution::with(['family', 'beneficiary']);

        if ($search = $request->input('search')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('aid_type', 'ilike', "%{$search}%")
                    ->orWhere('reference_number', 'ilike', "%{$search}%")
                    ->orWhereHas('family', fn ($family) => $family->where('case_number', 'ilike', "%{$search}%"));
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

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['created_by'] = auth()->id();
        $distribution = AidDistribution::create($data);
        $this->audit($request, 'created', $distribution, $data);

        return redirect('/aid-distributions')->with('status', 'Aid distribution recorded.');
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

    public function update(Request $request, AidDistribution $aidDistribution)
    {
        $data = $this->validated($request);
        $data['updated_by'] = auth()->id();
        $aidDistribution->update($data);
        $this->audit($request, 'updated', $aidDistribution, $data);

        return redirect('/aid-distributions/'.$aidDistribution->id)->with('status', 'Aid distribution updated.');
    }

    public function destroy(AidDistribution $aidDistribution)
    {
        $aidDistribution->delete();

        return redirect('/aid-distributions')->with('status', 'Aid distribution removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'family_id' => 'required|exists:families,id',
            'beneficiary_id' => 'nullable|exists:beneficiaries,id',
            'aid_type' => 'required|string|max:100',
            'distribution_date' => 'required|date',
            'quantity' => 'nullable|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'provider_organization' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
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
