<?php

namespace App\Http\Controllers;

use App\Models\AidDistribution;
use App\Models\Beneficiary;
use App\Models\Family;
use App\Models\Orphan;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function __invoke(Request $request, string $type): StreamedResponse
    {
        abort_unless($request->user()?->canExportData(), 403, __('messages.forbidden'));

        $query = match ($type) {
            'families' => $this->families($request),
            'beneficiaries' => $this->beneficiaries($request),
            'orphans' => $this->orphans($request),
            'aid' => $this->aid($request),
            default => abort(404, __('messages.not_found')),
        };

        return response()->streamDownload(function () use ($query): void {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            $first = true;

            foreach ($query->cursor() as $row) {
                $data = collect($row->toArray())->map(fn ($value) => $this->safeCell($value))->all();
                if ($first) {
                    fputcsv($output, array_map(fn ($column) => __('exports.headings.'.$column), array_keys($data)));
                    $first = false;
                }
                fputcsv($output, array_values($data));
            }

            if ($first) {
                fputcsv($output, [__('exports.no_records')]);
            }
            fclose($output);
        }, $type.'-'.now()->format('Ymd').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$type.'-'.now()->format('Ymd').'.csv"',
        ]);
    }

    private function families(Request $request)
    {
        return Family::query()->when($request->filled('search'), fn ($query) => $query->where(fn ($q) => $q->whereLike('case_number', '%'.$request->search.'%', caseSensitive: false)->orWhereLike('head_of_household_name', '%'.$request->search.'%', caseSensitive: false)->orWhereLike('national_id', '%'.$request->search.'%', caseSensitive: false)->orWhereLike('phone', '%'.$request->search.'%', caseSensitive: false)))->when($request->filled('governorate'), fn ($query) => $query->where('governorate', $request->governorate))->when($request->filled('provider_status'), fn ($query) => $query->where('provider_status', $request->provider_status))->latest();
    }

    private function beneficiaries(Request $request)
    {
        return Beneficiary::query()->when($request->filled('search'), fn ($query) => $query->where(fn ($q) => $q->whereLike('beneficiary_number', '%'.$request->search.'%', caseSensitive: false)->orWhereLike('full_name', '%'.$request->search.'%', caseSensitive: false)->orWhereLike('national_id', '%'.$request->search.'%', caseSensitive: false)))->when($request->filled('beneficiary_type'), fn ($query) => $query->where('beneficiary_type', $request->beneficiary_type))->when($request->filled('gender'), fn ($query) => $query->where('gender', $request->gender))->latest();
    }

    private function orphans(Request $request)
    {
        return Orphan::query()->with(['beneficiary', 'family'])->when($request->filled('search'), fn ($query) => $query->where(fn ($q) => $q->whereLike('orphan_number', '%'.$request->search.'%', caseSensitive: false)->orWhereLike('guardian_name', '%'.$request->search.'%', caseSensitive: false)->orWhereHas('beneficiary', fn ($b) => $b->whereLike('full_name', '%'.$request->search.'%', caseSensitive: false))))->latest();
    }

    private function aid(Request $request)
    {
        return AidDistribution::query()->when($request->filled('search'), fn ($query) => $query->where(fn ($q) => $q->whereLike('aid_type', '%'.$request->search.'%', caseSensitive: false)->orWhereLike('reference_number', '%'.$request->search.'%', caseSensitive: false)->orWhereHas('family', fn ($family) => $family->whereLike('case_number', '%'.$request->search.'%', caseSensitive: false))))->latest('distribution_date');
    }

    private function safeCell(mixed $value): mixed
    {
        if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        if (is_string($value) && preg_match('/^[=+\-@]/', $value)) {
            return "'".$value;
        }

        return $value;
    }
}
