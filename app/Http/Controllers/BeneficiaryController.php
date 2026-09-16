<?php

namespace App\Http\Controllers;

use App\Http\Requests\BeneficiaryRequest;
use App\Models\AuditLog;
use App\Models\Beneficiary;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeneficiaryController extends Controller
{
    public function index(Request $request)
    {
        $query = Beneficiary::with('family');
        if ($search = $request->input('search')) {
            $query->where(fn ($builder) => $builder->where('beneficiary_number', 'ilike', "%{$search}%")->orWhere('full_name', 'ilike', "%{$search}%")->orWhere('national_id', 'ilike', "%{$search}%"));
        }
        foreach (['gender', 'beneficiary_type', 'disability_status'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->input($field));
            }
        }

        return view('beneficiaries.index', ['beneficiaries' => $query->latest()->paginate(15)->withQueryString()]);
    }

    public function create()
    {
        return view('beneficiaries.form', ['beneficiary' => new Beneficiary, 'families' => Family::orderBy('case_number')->get()]);
    }

    public function store(BeneficiaryRequest $request)
    {
        $data = $request->validated();
        $data['beneficiary_number'] = 'BEN-'.now()->year.'-'.str_pad((string) (Beneficiary::withTrashed()->max('id') + 1), 4, '0', STR_PAD_LEFT);
        $data['created_by'] = auth()->id();
        $beneficiary = DB::transaction(function () use ($data, $request) {
            $beneficiary = Beneficiary::create($data);
            $this->audit($request, 'created', $beneficiary, $data);

            return $beneficiary;
        });

        return redirect()->route('beneficiaries.index')->with('status', 'Beneficiary created.');
    }

    public function show(Beneficiary $beneficiary)
    {
        return view('beneficiaries.show', ['beneficiary' => $beneficiary->load('family', 'orphan')]);
    }

    public function edit(Beneficiary $beneficiary)
    {
        return view('beneficiaries.form', ['beneficiary' => $beneficiary, 'families' => Family::orderBy('case_number')->get()]);
    }

    public function update(BeneficiaryRequest $request, Beneficiary $beneficiary)
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();
        $old = $beneficiary->toArray();
        DB::transaction(function () use ($data, $old, $beneficiary, $request): void {
            $beneficiary->update($data);
            $this->audit($request, 'updated', $beneficiary, $data, $old);
        });

        return redirect()->route('beneficiaries.show', $beneficiary)->with('status', 'Beneficiary updated.');
    }

    public function destroy(Beneficiary $beneficiary)
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        $beneficiary->delete();

        return back()->with('status', 'Beneficiary archived.');
    }

    private function audit(Request $request, string $action, Beneficiary $beneficiary, array $new, ?array $old = null): void
    {
        AuditLog::create(['user_id' => auth()->id(), 'action' => $action, 'model_type' => 'Beneficiary', 'model_id' => $beneficiary->id, 'description' => "Beneficiary {$action}", 'old_values' => $old, 'new_values' => $new, 'ip_address' => $request->ip()]);
    }
}
