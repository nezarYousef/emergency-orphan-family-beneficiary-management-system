<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Beneficiary;
use App\Models\Family;
use App\Models\Orphan;
use Illuminate\Http\Request;

class OrphanController extends Controller
{
    public function index(Request $request)
    {
        $query = Orphan::with(['beneficiary', 'family']);

        if ($search = $request->input('search')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('orphan_number', 'ilike', "%{$search}%")
                    ->orWhere('guardian_name', 'ilike', "%{$search}%")
                    ->orWhereHas('beneficiary', fn ($beneficiary) => $beneficiary->where('full_name', 'ilike', "%{$search}%"));
            });
        }

        return view('orphans.index', ['orphans' => $query->latest()->paginate(15)->withQueryString()]);
    }

    public function create()
    {
        return view('orphans.form', [
            'orphan' => new Orphan,
            'families' => Family::orderBy('case_number')->get(),
            'beneficiaries' => Beneficiary::orderBy('full_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['orphan_number'] = 'ORP-'.now()->year.'-'.str_pad((string) (Orphan::withTrashed()->max('id') + 1), 4, '0', STR_PAD_LEFT);
        $data['created_by'] = auth()->id();
        $orphan = Orphan::create($data);
        $this->audit($request, 'created', $orphan, $data);

        return redirect('/orphans')->with('status', 'Orphan record created.');
    }

    public function show(Orphan $orphan)
    {
        return view('orphans.show', ['orphan' => $orphan->load(['beneficiary', 'family'])]);
    }

    public function edit(Orphan $orphan)
    {
        return view('orphans.form', [
            'orphan' => $orphan,
            'families' => Family::orderBy('case_number')->get(),
            'beneficiaries' => Beneficiary::orderBy('full_name')->get(),
        ]);
    }

    public function update(Request $request, Orphan $orphan)
    {
        $data = $this->validated($request);
        $data['updated_by'] = auth()->id();
        $orphan->update($data);
        $this->audit($request, 'updated', $orphan, $data);

        return redirect('/orphans/'.$orphan->id)->with('status', 'Orphan record updated.');
    }

    public function destroy(Orphan $orphan)
    {
        $orphan->delete();

        return back()->with('status', 'Orphan record archived.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            'family_id' => 'required|exists:families,id',
            'orphan_status' => 'required|string|max:50',
            'father_status' => 'required|string|max:50',
            'mother_status' => 'required|string|max:50',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relationship' => 'nullable|string|max:100',
            'school_status' => 'nullable|string|max:100',
            'sponsorship_status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);
    }

    private function audit(Request $request, string $action, Orphan $orphan, array $data): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => 'Orphan',
            'model_id' => $orphan->id,
            'description' => "Orphan {$action}",
            'new_values' => $data,
            'ip_address' => $request->ip(),
        ]);
    }
}
