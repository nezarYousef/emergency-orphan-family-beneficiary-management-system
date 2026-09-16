<?php

namespace App\Http\Controllers;

use App\Http\Requests\FamilyRequest;
use App\Models\AuditLog;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FamilyController extends Controller
{
    public function index(Request $request)
    {
        $query = Family::query();
        if ($search = $request->input('search')) {
            $query->where(fn ($builder) => $builder->where('case_number', 'ilike', "%{$search}%")->orWhere('head_of_household_name', 'ilike', "%{$search}%")->orWhere('national_id', 'ilike', "%{$search}%")->orWhere('phone', 'ilike', "%{$search}%"));
        }
        foreach (['governorate', 'area', 'provider_status', 'vulnerability_status'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->input($field));
            }
        }

        return view('families.index', ['families' => $query->withCount('beneficiaries')->latest()->paginate(15)->withQueryString()]);
    }

    public function create()
    {
        return view('families.form', ['family' => new Family]);
    }

    public function store(FamilyRequest $request)
    {
        $data = $request->validated();
        $data['case_number'] = 'FAM-'.now()->year.'-'.str_pad((string) (Family::withTrashed()->max('id') + 1), 4, '0', STR_PAD_LEFT);
        $data['created_by'] = auth()->id();
        $family = DB::transaction(function () use ($data, $request) {
            $family = Family::create($data);
            $this->audit($request, 'created', $family, $data);

            return $family;
        });

        return redirect()->route('families.index')->with('status', 'Family created.');
    }

    public function show(Family $family)
    {
        return view('families.show', ['family' => $family->load('beneficiaries', 'orphans.beneficiary', 'aidDistributions')]);
    }

    public function edit(Family $family)
    {
        return view('families.form', compact('family'));
    }

    public function update(FamilyRequest $request, Family $family)
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();
        $old = $family->toArray();
        DB::transaction(function () use ($data, $old, $family, $request): void {
            $family->update($data);
            $this->audit($request, 'updated', $family, $data, $old);
        });

        return redirect()->route('families.show', $family)->with('status', 'Family updated.');
    }

    public function destroy(Family $family)
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        $family->delete();

        return redirect()->route('families.index')->with('status', 'Family archived.');
    }

    private function audit(Request $request, string $action, Family $family, array $new, ?array $old = null): void
    {
        AuditLog::create(['user_id' => auth()->id(), 'action' => $action, 'model_type' => 'Family', 'model_id' => $family->id, 'description' => "Family {$action}", 'old_values' => $old, 'new_values' => $new, 'ip_address' => $request->ip()]);
    }
}
