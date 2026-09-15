@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center"><h1>Aid Distributions</h1><a class="btn btn-primary" href="/aid-distributions/create">Record Aid</a></div>
<form class="row g-2 my-3"><div class="col-md-8"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search aid type, reference, family case"></div><div class="col-auto"><button class="btn btn-outline-primary">Search</button></div></form>
<div class="table-responsive card"><table class="table table-striped mb-0"><thead><tr><th>Date</th><th>Family</th><th>Beneficiary</th><th>Type</th><th>Amount</th><th>Reference</th></tr></thead><tbody>
@forelse($distributions as $distribution)<tr><td><a href="/aid-distributions/{{ $distribution->id }}">{{ $distribution->distribution_date?->format('Y-m-d') }}</a></td><td>{{ $distribution->family?->case_number }}</td><td>{{ $distribution->beneficiary?->full_name ?? 'Family-level' }}</td><td>{{ $distribution->aid_type }}</td><td>{{ $distribution->amount }} {{ $distribution->currency }}</td><td>{{ $distribution->reference_number }}</td></tr>@empty<tr><td colspan="6" class="p-4">No aid distributions found.</td></tr>@endforelse
</tbody></table></div><div class="mt-3">{{ $distributions->links() }}</div>
@endsection
